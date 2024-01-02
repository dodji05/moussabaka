<?php

namespace App\Controller;

use App\Entity\EcoledeProvenance;
use App\Form\EcoledeProvenanceType;
use App\Repository\EcoledeProvenanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ecolede/provenance')]
class EcoledeProvenanceController extends AbstractController
{
    #[Route('/', name: 'app_ecolede_provenance_index',methods: ['GET', 'POST'])]
    public function index(Request $request,EcoledeProvenanceRepository $ecoledeProvenanceRepository): Response
    {
        $ecoledeProvenance = new EcoledeProvenance();
        $form = $this->createForm(EcoledeProvenanceType::class, $ecoledeProvenance);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ecoledeProvenanceRepository->save($ecoledeProvenance,true);

            return $this->redirectToRoute('app_ecolede_provenance_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('ecolede_provenance/index.html.twig', [
            'ecolede_provenances' => $ecoledeProvenanceRepository->findAll(),
            'form'=>$form,
        ]);
    }

    #[Route('/new', name: 'app_ecolede_provenance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ecoledeProvenance = new EcoledeProvenance();
        $form = $this->createForm(EcoledeProvenanceType::class, $ecoledeProvenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ecoledeProvenance);
            $entityManager->flush();

            return $this->redirectToRoute('app_ecolede_provenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ecolede_provenance/new.html.twig', [
            'ecolede_provenance' => $ecoledeProvenance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ecolede_provenance_show', methods: ['GET'])]
    public function show(EcoledeProvenance $ecoledeProvenance): Response
    {
        return $this->render('ecolede_provenance/show.html.twig', [
            'ecolede_provenance' => $ecoledeProvenance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ecolede_provenance_edit', options: ["expose" => true], methods: ['GET', 'POST'])]
    public function edit(Request $request, EcoledeProvenance $ecoledeProvenance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EcoledeProvenanceType::class, $ecoledeProvenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ecolede_provenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ecolede_provenance/edit.html.twig', [
            'ecolede_provenance' => $ecoledeProvenance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ecolede_provenance_delete', methods: ['POST'])]
    public function delete(Request $request, EcoledeProvenance $ecoledeProvenance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ecoledeProvenance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ecoledeProvenance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ecolede_provenance_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/create-form", name="ecole_edits",options={"expose"=true})
     */
    #[Route('/{id}/create-form', name: 'ecole_edit', options: ["expose" => true])]
    public function ajaxEditFormAction(Request $request, EcoledeProvenance $ecoledeProvenance)
    {
        $editForm =  $this->createForm(EcoledeProvenanceType::class, $ecoledeProvenance);

        return $this->render('modal/modal_edit.html.twig', array(
            'Categories' =>  $ecoledeProvenance,
            'isModal' => true,
            'single' => true,
            'form' => $editForm->createView(),
        ));
    }

}
