<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\CandidatRepository;
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
                    ->innerJoin('c.categorie','s')
                    ->innerJoin('c.ecoledeProvenance','e')
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
}
