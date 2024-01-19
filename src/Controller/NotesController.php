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
    public function questionParticipant(Candidat $candidat,QuestionRepository $questionRepository){
//        dd($candidatRepository->participant());
        $questionnaires = $questionRepository->findBy(['candidat'=>$candidat]);
//        dd($questionnaires);
        return $this->render('candidat/questionnaires_participants.html.twig', [
            'questions' =>  $questionnaires,
        ]);
    }

    #[Route('/notes/{id}/questions', name: 'app_notes_questions')]
    public function noteQuestions(Request $request,Question $question,NotesRepository $notesRepository,JuryRepository $juryRepository,SourateRepository $sourateRepository){
        $note = new Notes();
        $note->setQuestions($question);
        $note->setJury($juryRepository->findOneBy(['membres'=>$this->getUser(),'annnee'=>1]));
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
