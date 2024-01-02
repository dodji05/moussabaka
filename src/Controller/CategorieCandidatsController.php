<?php

namespace App\Controller;

use App\Entity\CategorieCandidats;
use App\Form\CategorieCandidatsType;
use App\Repository\CategorieCandidatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie/candidats')]
class CategorieCandidatsController extends AbstractController
{
    #[Route('/', name: 'app_categorie_candidats_index', methods: ['GET', 'POST'])]
    public function index(Request $request,CategorieCandidatsRepository $categorieCandidatsRepository): Response
    {
        $categorieCandidat = new CategorieCandidats();
        $form = $this->createForm(CategorieCandidatsType::class, $categorieCandidat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categorieCandidatsRepository->save($categorieCandidat, true);

            return $this->redirectToRoute('app_categorie_candidats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_candidats/index.html.twig', [
            'categorie_candidats' => $categorieCandidatsRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_categorie_candidats_new', options: ["expose" => true], methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieCandidatsRepository $categorieCandidatsRepository): Response
    {
        $categorieCandidat = new CategorieCandidats();
        $form = $this->createForm(CategorieCandidatsType::class, $categorieCandidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieCandidatsRepository->save($categorieCandidat, true);

            return $this->redirectToRoute('app_categorie_candidats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_candidats/new.html.twig', [
            'categorie_candidat' => $categorieCandidat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_candidats_show', methods: ['GET'])]
    public function show(CategorieCandidats $categorieCandidat): Response
    {
        return $this->render('categorie_candidats/show.html.twig', [
            'categorie_candidat' => $categorieCandidat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_candidats_edit', options: ["expose" => true], methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieCandidats $categorieCandidat, CategorieCandidatsRepository $categorieCandidatsRepository): Response
    {
        $form = $this->createForm(CategorieCandidatsType::class, $categorieCandidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieCandidatsRepository->save($categorieCandidat, true);
            $this->addFlash('success', "la categorie a ete modifier avec success ");

            return $this->redirectToRoute('app_categorie_candidats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_candidats/edit.html.twig', [
            'categorie_candidat' => $categorieCandidat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_candidats_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieCandidats $categorieCandidat, CategorieCandidatsRepository $categorieCandidatsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieCandidat->getId(), $request->request->get('_token'))) {
            $categorieCandidatsRepository->remove($categorieCandidat, true);
        }

        return $this->redirectToRoute('app_categorie_candidats_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/{id}/create-form", name="categorie_edits",options={"expose"=true})
     */
    #[Route('/{id}/create-form', name: 'categorie_edit', options: ["expose" => true], methods: ['POST'])]
    public function ajaxEditFormAction(Request $request, CategorieCandidats $categorieCandidats)
    {
        $editForm = $this->createForm(CategorieCandidatsType::class,  $categorieCandidats);

        return $this->render('modal/modal_edit.html.twig', array(
            'Categories' => $categorieCandidats,
            'isModal' => true,
            'single' => true,
            'form' => $editForm->createView(),
        ));
    }

    public function handleEditForm(Request $request, $entity, $form)
    {


        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //$entity = $form->getData();

            $em->persist($entity);
            $em->flush();
            $this->addFlash("sucess",'Le lieu d\'interet a été modifié avec sucess');
            return true;
        }

        return false;
    }
}
