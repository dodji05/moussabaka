<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Form\CandidatType;
use App\Repository\CandidatRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidat')]
class CandidatController extends AbstractController
{
    #[Route('/', name: 'app_candidat_index', methods: ['GET'])]
    public function index(CandidatRepository $candidatRepository): Response
    {
        return $this->render('candidat/index.html.twig', [
            'candidats' => $candidatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_candidat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CandidatRepository $candidatRepository,FileUploader $fileUploader): Response
    {
        $candidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPhoto = $candidat->getPhoto();

            if ($form->get('img')->getData()) {
                // Supprimer l'ancienne photo
                if ($oldPhoto) {
                    $fileUploader->remove($oldPhoto);
                }
                $image = $form->get('img')->getData();
                $fichier = $fileUploader->upload($image);
                $candidat->setPhoto($fichier);
            }

            $candidatRepository->save($candidat, true);
            $this->addFlash('success', "Le candidat :" .$candidat->getFullName() ."  a été ajouté avec succès ");


            return $this->redirectToRoute('app_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidat/new.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_candidat_show', methods: ['GET'])]
    public function show(Candidat $candidat): Response
    {
        return $this->render('candidat/show.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_candidat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidat $candidat, CandidatRepository $candidatRepository,FileUploader $fileUploader): Response
    {
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPhoto = $candidat->getPhoto();


            if ($form->get('img')->getData()) {

                // Supprimer l'ancienne photo
                if ($oldPhoto) {
                    $fileUploader->remove($oldPhoto);
                }

                $image = $form->get('img')->getData();
                $fichier = $fileUploader->upload($image);
                $candidat->setPhoto($fichier);
            }
            $candidatRepository->save($candidat, true);
            $this->addFlash('success', "Le candidat :" .$candidat->getFullName() ."  a été mis à jour  avec succès ");


            return $this->redirectToRoute('app_candidat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidat/edit.html.twig', [
            'candidat' => $candidat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_candidat_delete', methods: ['POST'])]
    public function delete(Request $request, Candidat $candidat, CandidatRepository $candidatRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidat->getId(), $request->request->get('_token'))) {
            $candidatRepository->remove($candidat, true);
        }

        return $this->redirectToRoute('app_candidat_index', [], Response::HTTP_SEE_OTHER);
    }
}
