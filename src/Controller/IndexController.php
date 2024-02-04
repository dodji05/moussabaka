<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Choix;
use App\Entity\Question;
use App\Form\SearchType;
use App\Repository\CandidatRepository;
use App\Repository\CategorieCandidatsRepository;
use App\Repository\ChoixRepository;
use App\Repository\QuestionRepository;
use App\Repository\SourateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private array $cand = [];

    #[Route('/', name: 'app_index')]
    public function index(CandidatRepository $candidatRepository): Response
    {

        $nbCandidatnaba = count($candidatRepository->findBy(['categorie' => 1]));
        $nbCandidatMoulk = count($candidatRepository->findBy(['categorie' => 2]));
        $nbCandidatMoujadala = count($candidatRepository->findBy(['categorie' => 3]));
        $nbCandidatAhqaf = count($candidatRepository->findBy(['categorie' => 4]));
        $nbCandidatYassin = count($candidatRepository->findBy(['categorie' => 5]));
        $nbCandidatAnkabout = count($candidatRepository->findBy(['categorie' => 6]));
        $nbCandidatnisf = count($candidatRepository->findBy(['categorie' => 7]));
        $nbCandidattawba = count($candidatRepository->findBy(['categorie' => 8]));
        $nbCandidatkamil = count($candidatRepository->findBy(['categorie' => 9]));

        //dd($nbCandidatnisf);


        // dd($nbCandidatnaba);


        return $this->render('index.html.twig', [

            'naba' => $nbCandidatnaba,
            'moulk' => $nbCandidatMoulk,
            'moujadala' => $nbCandidatMoujadala,
            'ahqaf' => $nbCandidatAhqaf,
            'yassin' => $nbCandidatYassin,
            'ankabout' => $nbCandidatAnkabout,
            'nisf' => $nbCandidatnisf,
            'tawba' => $nbCandidattawba,
            'kamil' => $nbCandidatkamil
        ]);
    }


    #[Route('/listecandidats/{slug}', name: 'app_index_liste_candidats')]
    public function listeCandidats(CandidatRepository $candidatRepository, $slug, PaginatorInterface $paginator, Request $request): Response
    {
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);
        $candidats = $candidatRepository->NombreCandidatsParCategorie($slug);
        $queryBuilder = $candidatRepository->createQueryBuilder('c')
            ->innerJoin('c.categorie', 's')
            ->innerJoin('c.ecoledeProvenance', 'e')
            ->Where('s.name = :val')
            ->setParameter('val', $slug);;

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchTerm = $searchForm->get('search')->getData();

            if ($searchTerm) {
                $queryBuilder
                    ->andWhere(
                        $queryBuilder->expr()->orX(
                            $queryBuilder->expr()->like('c.mom', ':searchTerm'),
                            $queryBuilder->expr()->like('c.prenom', ':searchTerm')
                        )
                    )
                    ->setParameter('searchTerm', "%$searchTerm%");

            }
        }

        $candidats1 = $queryBuilder->getQuery()->getResult();
        //dump($candidats1);
        shuffle($candidats1);
        // Paginate the results using KnpPaginatorBundle
        $pagination = $paginator->paginate(
            $candidats1,
            $request->query->getInt('page', 1),
            12 // Number of items per page
        );

        return $this->render('listecandidatparcategorie.html.twig', [
            'candidats' => $candidats1,
            'pagination' => $pagination,
            'searchForm' => $searchForm->createView(),
            'slug' => $slug

        ]);


    }
//    public function index(CandidatRepository $candidatRepository,PaginatorInterface $paginator,Request $request): Response
//    {
//        $candidats=$candidatRepository->findAll();
//        $pagination=$paginator->paginate(
//            $candidats,
//            $request->query->getInt('page',1),
//            10
//        );
//        return $this->render('index.html.twig', [
//            'candidats'=>$candidats,
//            'pagination'=>$pagination
//
//
//        ]);
//    }

    #[Route('/questions-series/{id}/candidats', name: 'app_questions_serie')]
    public function selectSerieQuestion(Candidat $candidat, ChoixRepository $choixRepository, CandidatRepository $candidatRepository, PaginatorInterface $paginator, Request $request): Response

    {
        $debutSourate = (int)$candidat->getCategorie()->getDebutSourate();
        $nbCandidatParCategorie = $candidatRepository->count(['categorie' => $candidat->getCategorie()]);

        $choix = array();
        // Ajout d'éléments au tableau avec des indices dynamiques
        for ($i = 0; $i < $nbCandidatParCategorie + 3; $i++) {
            $choix[$i][$candidat->getCategorie()->getId()] = $i;
        }
        $dejaChoisis = $choixRepository->findBy(['categorie' => $candidat->getCategorie()->getId()]);
        foreach ($dejaChoisis as $item) {
            unset($choix[$item->getChoiceIndex()]);
        }
//        dd(  $choix[9][2]);
        //   unset($choix[9][2]);
//        dd(  $choix);
        $numbers = range($debutSourate, 114);
        shuffle($numbers);
        $Sourate = array_slice($numbers, 1, 3);
        return $this->render('choix_serie_question.html.twig', [
            'choix' => $choix,
            'candidat' => $candidat,
            'sourate' => $Sourate,
            "nbrecandidat" => $nbCandidatParCategorie
        ]);
    }

    #[Route('/questions-list/{id}/candidats/{position}/sourate', name: 'app_questions_liste')]
    public function questions(Candidat $candidat, SourateRepository $sourateRepository, ChoixRepository $choixRepository,
                              Request  $request, QuestionRepository $questionRepository, EntityManagerInterface $entityManager): Response

    {
        $position = (int)$request->get('position');

        $choix = $choixRepository->findBy(['candidat' => $candidat->getId(), 'categorie' => $candidat->getCategorie()->getId()]);

        if (!$choix) {
            $index = new Choix();
            $index->setChoiceIndex($position);
            $index->setCategorie($candidat->getCategorie()->getId());
            $index->setCandidat($candidat->getId());
            $choixRepository->save($index, true);

        }


        $nbreDePartie = 3;

        // debut de Sourate de la categorie de l'eleve
        $debutSourate = (int)$candidat->getCategorie()->getDebutSourate();

        // intervalle de Sourates de la categorie de l'eleve
        $intervalleSourate = range($debutSourate, 114);


        // Calculez la taille de chaque partie en utilisant la division entière

        $tailleSourate = floor(count($intervalleSourate) / $nbreDePartie);
        $resteSourate = count($intervalleSourate) % $nbreDePartie;
        $listeSourateSelectionne = [];
        $offset = 0;
        for ($i = 0; $i < $nbreDePartie; $i++) {
            $length = $tailleSourate + ($resteSourate > 0 ? 1 : 0);
            $partie[] = array_slice($intervalleSourate, $offset, $length);
            $offset += $length;
            $resteSourate = max(0, $resteSourate - 1);

            $listeSourate[] = $sourateRepository->findBy(['surahnumber' => $partie[$i], 'isReaded' => false]);
//            $listeSourateSelectionne[]  = $partie[$i][array_rand($partie[$i])];
            $listeSourateSelectionne[] = $listeSourate[$i][array_rand($listeSourate[$i])];
        }
//        dd($listeSourateSelectionne);
        $dejaPasse = $questionRepository->findOneBy(['candidat' => $candidat]);

//        if (!$dejaPasse) {

        if ($listeSourateSelectionne and !$dejaPasse) {
            foreach ($listeSourateSelectionne as $sourate) {
                $question = new Question();
                $question->setCandidat($candidat);
                $question->setSourate($sourate);
                $entityManager->persist($question);
            }
            $entityManager->flush();
        }
//        }

        // Sélectionner un sous-ensemble de 3 questions parmi les sourates non lues
//        $listeQuestions =  array_slice( $partieSeclionnee,0,3);

        $questionsTest = [];
        $nbVersetsAlire = 15;
        // Récupérer les 15 premières verset a partir de la sourate sélectionnée
        //dd($partie);

//            foreach (   $listeSourateSelectionne  as $item){
        //    dd($partie,$item[$i]);
        for ($i = 0; $i < $nbreDePartie; $i++) {
            $questionsTest[] = $sourateRepository->createQueryBuilder('s')
                ->where('s.id >= :id')
                ->andWhere('s.surahnumber IN (:sura)')
                ->setParameter('id', $listeSourateSelectionne[$i]->getId())
                ->setParameter('sura', $partie[$i])
                ->orderBy('s.id', 'ASC')
                ->setMaxResults($nbVersetsAlire)
                ->getQuery()
                ->getResult();
        }


//        dd(  $listeQuestions, $questionsTest );

        return $this->render('serie_question.html.twig', [
            'candidat' => $candidat,
            'versets' => $questionsTest,


        ]);
    }


}
