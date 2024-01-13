<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Form\SearchType;
use App\Repository\CandidatRepository;
use App\Repository\SourateRepository;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(CandidatRepository $candidatRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);

        $queryBuilder = $candidatRepository->createQueryBuilder('c');

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchTerm = $searchForm->get('search')->getData();

            // Modify the queryBuilder to include your search logic
            if ($searchTerm) {
                $queryBuilder
                    ->innerJoin('c.categorie', 's')
                    ->innerJoin('c.ecoledeProvenance', 'e')
                    ->orWhere('c.mom LIKE :searchTerm')
                    ->orWhere('c.prenom LIKE :searchTerm')
                    ->orWhere('c.numeroCandidat LIKE :searchTerm')
                    ->orWhere('c.age LIKE :searchTerm')
                    ->orWhere('s.name LIKE :searchTerm')
                    ->orWhere('c.localite LIKE :searchTerm')
                    ->orWhere('e.nom LIKE :searchTerm')
                    ->setParameter('searchTerm', "%$searchTerm%");
            }
        }

        $candidats = $queryBuilder->getQuery()->getResult();

        // Paginate the results using KnpPaginatorBundle
        $pagination = $paginator->paginate(
            $candidats,
            $request->query->getInt('page', 1),
            10 // Number of items per page
        );

        return $this->render('index.html.twig', [
            'pagination' => $pagination,
            'searchForm' => $searchForm->createView(),
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
    public function selectSerieQuestion(Candidat $candidat, CandidatRepository $candidatRepository, PaginatorInterface $paginator, Request $request): Response

    {
        $debutSourate = (int)$candidat->getCategorie()->getDebutSourate();
        $numbers = range($debutSourate, 114);
        shuffle($numbers);
        $Sourate = array_slice($numbers, 1, 3);
        return $this->render('choix_serie_question.html.twig', [
            'candidat' => $candidat,
            'sourate' => $Sourate
        ]);
    }

    #[Route('/questions-list/{id}/candidats/{sourate}/sourate', name: 'app_questions_liste')]
    public function questions(Candidat $candidat, SourateRepository $sourateRepository, PaginatorInterface $paginator, Request $request): Response

    {
        // debut de Sourate de la categorie de l'eleve
        $debutSourate = (int)$candidat->getCategorie()->getDebutSourate();

        // intervalle de Sourates de la categorie de l'eleve
        $intervalleSourate = range($debutSourate, 114);

        // Récupérer les sourates non lues de l'intervalle et les mélanger
        $listeSourate = $sourateRepository->findBy(['surahnumber' =>  $intervalleSourate, 'isReaded' => false]);
        shuffle($listeSourate);

        // Sélectionner un sous-ensemble de 3 questions parmi les sourates non lues
        $listeQuestions =  array_slice( $listeSourate,0,3);

        $questionsTest=[];
        $nbVersetsAlire = 15;
        // Récupérer les 15 premières verset a partir de la sourate sélectionnée
        foreach (  $listeQuestions  as $item){
            $questionsTest[]= $sourateRepository->createQueryBuilder('s')
                ->where('s.id >= :id')
                ->setParameter('id', $item->getId())
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
