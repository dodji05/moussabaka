<?php

namespace App\Controller;

use App\Repository\SourateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/questions')]
class QuestionsController extends AbstractController
{
    #[Route('/', name: 'app_jury_questions', methods: ['GET'])]
    public function generateQuestions(SourateRepository $sourateRepository)
    {
        $radonQuestion = [];
        for ($i = 1; $i <= 3; $i++) {
            $radonQuestion[] = rand(78,114);
        }

        $numbers = range(78, 114);
        shuffle($numbers);
//        foreach ($numbers as $number) {
//            echo "$number ";
//        }
        $nabaQuestions =  array_slice( $numbers,0,3);
        $test = $sourateRepository->verset(90);
        dd( $nabaQuestions,$radonQuestion,$test );
    }
}
