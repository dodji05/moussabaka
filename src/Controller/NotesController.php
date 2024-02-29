<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Notes;
use App\Entity\Question;
use App\Form\NotesType;
use App\Repository\CandidatRepository;
use App\Repository\JuryRepository;
use App\Repository\NotesRepository;
use App\Repository\QuestionRepository;

use App\Repository\SourateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/notes')]
class NotesController extends AbstractController
{

    #[Route('/les-participants', name: 'app_notes_liste_participant')]
    public function listeParticipant( CandidatRepository $candidatRepository){
//        dd($candidatRepository->participant());
        return $this->render('candidat/liste_participants.twig', [
            'candidats' => $candidatRepository->participant(),
        ]);
    }

    #[Route('/les-questions/{id}/participant', name: 'app_notes_liste_questions')]
    public function questionParticipant(Candidat $candidat,QuestionRepository $questionRepository,NotesRepository $notesRepository,JuryRepository $juryRepository){

        $jury =   $juryRepository->findOneBy(['membres'=>$this->getUser(),'annnee'=>1]);

        // recuperation des questions du candidat deja notees par le jury encours
        $notes = $notesRepository->noteParCandidat($candidat->getId(),$jury->getId());
        $quest = [];
        foreach ($notes  as $note){
            $quest[] = $note->getQuestions()->getId();
        }

        // recuperation des questions du candidat non notees par le jury en cours
        $questionnaires1 = $questionRepository->questionsNonNotees($quest,$candidat,$jury);

        $result = array_merge($questionnaires1,  $notes);
        $questions = [];
        foreach ($questionnaires1  as $question){
            $questions[] = [
                "id" =>$question->getId(),
                "sourate" =>$question->getSourate()->getSurahnumber(),
                "sourate_arabic" =>$question->getSourate()->getSurahnamearabic(),
                "verset" =>$question->getSourate()->getAyahnumber(),
                "memorisation" =>'',
                "tajwid" =>'',
                "lecture" =>'',
                "moyenne" =>'',
                "action"=>true
                ];
        }
        foreach ($notes  as $question){
            $questions[] = [
                "id" =>$question->getQuestions()->getId(),
                "sourate" =>$question->getQuestions()->getSourate()->getSurahnumber(),
                "sourate_arabic" =>$question->getQuestions()->getSourate()->getSurahnamearabic(),
                "verset" =>$question->getQuestions()->getSourate()->getAyahnumber(),
                "memorisation" =>(int)$question->getMemorisation(),
                "tajwid" =>(int)$question->getTajwid(),
                "lecture" =>(int)$question->getLecture(),
                "moyenne" =>((int)$question->getLecture()+(int)$question->getTajwid() +(int)$question->getMemorisation()) / 3,
                "action"=>false
            ];
        }
//        dd(  $questions );
        return $this->render('candidat/questionnaires_participants.html.twig', [
            'questions' =>   $questions,
            'candidat'=>$candidat
        ]);
    }

    #[Route('/notes/{id}/questions', name: 'app_notes_questions')]
    public function noteQuestions(Request $request,Question $question,NotesRepository $notesRepository,JuryRepository $juryRepository,SourateRepository $sourateRepository,QuestionRepository $questionRepository){
        $note = new Notes();
        $note->setQuestions($question);
        $note->setJury($juryRepository->findOneBy(['membres'=>$this->getUser(),'annnee'=>1]));
      //  dd(($juryRepository->findOneBy(['membres'=>$this->getUser(),'annnee'=>1])));
        $form = $this->createForm(NotesType::class, $note);
        $candidat = $question->getCandidat();

        $nbVersetsAlire = 15;
        $listeVersetAlire = $sourateRepository->createQueryBuilder('s')
            ->where('s.id >= :id')
//            ->andWhere('s.surahnumber IN (:sura)')
//            ->andWhere('s. IN (:sura)')
            ->setParameter('id', $question->getSourate()->getId())
//            ->setParameter('sura', $partie[$i])
            ->orderBy('s.id', 'ASC')
            ->setMaxResults($nbVersetsAlire)
            ->getQuery()
            ->getResult();
        $form->handleRequest($request);
     //   dd( $form);
        if ($form->isSubmitted() && $form->isValid())

        {
            $question->setNote(true);
            $questionRepository->save( $question,true);
            $notesRepository->save($note, true);

            return $this->redirectToRoute('app_notes_liste_questions', ['id'=>$candidat->getId()], Response::HTTP_SEE_OTHER);

        }
        return $this->render('candidat/notations_questions.html.twig', [
            'form' =>   $form,
            'candidat'=> $candidat,
            'versets'=> $listeVersetAlire,
            'question'=> $question
        ]);
    }
}
