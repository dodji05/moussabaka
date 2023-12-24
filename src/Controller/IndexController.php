<?php

namespace App\Controller;

use App\Repository\CandidatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(CandidatRepository $candidatRepository,PaginatorInterface $paginator): Response
    {
        $candidats=$candidatRepository->findAll();
        return $this->render('index.html.twig', [
            'candidats'=>$candidats,


        ]);
    }
}
