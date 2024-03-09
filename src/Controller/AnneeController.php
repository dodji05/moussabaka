<?php

namespace App\Controller;

use App\Entity\Annee;
use App\Form\AnneeType;
use App\Repository\AnneeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annee')]
class AnneeController extends AbstractController
{
    #[Route('/', name: 'app_annee_index', methods: ['GET'])]
    public function index(AnneeRepository $anneeRepository): Response
    {
        return $this->render('annee/index.html.twig', [
            'annees' => $anneeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_annee_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,AnneeRepository $anneeRepository): Response
    {
        $annee = new Annee();
        $form = $this->createForm(AnneeType::class, $annee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $found=$anneeRepository->findOneBy([
                'annee'=>$form->get('annee')->getData()
            ]);
           // dd($found);
            if($form->get('annee')->getData()==""){
                $this->addFlash('danger', "Vous devrez saisir une année");
                return $this->redirectToRoute('app_annee_new', [], Response::HTTP_SEE_OTHER);
            }elseif ($found){
                $this->addFlash('warning', "L'annéee :" .$form->get('annee')->getData() ."  a été  déjà ajouté  ");


                return $this->redirectToRoute('app_annee_new', [], Response::HTTP_SEE_OTHER);
            }else{
                $entityManager->persist($annee);
                $entityManager->flush();
                $this->addFlash('success', "L'année : " .$form->get('annee')->getData(). " a été ajouté avec succès" );

                return $this->redirectToRoute('app_annee_index', [], Response::HTTP_SEE_OTHER);
            }

        }

        return $this->render('annee/new.html.twig', [
            'annee' => $annee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annee_show', methods: ['GET'])]
    public function show(Annee $annee): Response
    {
        return $this->render('annee/show.html.twig', [
            'annee' => $annee,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annee $annee, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnneeType::class, $annee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_annee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annee/edit.html.twig', [
            'annee' => $annee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annee_delete', methods: ['POST'])]
    public function delete(Request $request, Annee $annee, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annee->getId(), $request->request->get('_token'))) {
            $entityManager->remove($annee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_annee_index', [], Response::HTTP_SEE_OTHER);
    }
}
