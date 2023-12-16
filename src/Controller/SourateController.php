<?php

namespace App\Controller;

use App\Entity\Sourate;
use App\Form\SourateType;
use App\Repository\SourateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sourate')]
class SourateController extends AbstractController
{
    #[Route('/', name: 'app_sourate_index', methods: ['GET'])]
    public function index(SourateRepository $sourateRepository): Response
    {
        return $this->render('sourate/index.html.twig', [
            'sourates' => $sourateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sourate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sourate = new Sourate();
        $form = $this->createForm(SourateType::class, $sourate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sourate);
            $entityManager->flush();

            return $this->redirectToRoute('app_sourate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sourate/new.html.twig', [
            'sourate' => $sourate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sourate_show', methods: ['GET'])]
    public function show(Sourate $sourate): Response
    {
        return $this->render('sourate/show.html.twig', [
            'sourate' => $sourate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sourate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sourate $sourate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SourateType::class, $sourate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sourate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sourate/edit.html.twig', [
            'sourate' => $sourate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sourate_delete', methods: ['POST'])]
    public function delete(Request $request, Sourate $sourate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sourate->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sourate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sourate_index', [], Response::HTTP_SEE_OTHER);
    }
}
