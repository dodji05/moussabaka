<?php

namespace App\Controller;

use App\Entity\Jury;
use App\Form\JuryType;
use App\Repository\JuryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/jury')]
class JuryController extends AbstractController
{
    #[Route('/', name: 'app_jury_index', methods: ['GET'])]
    public function index(JuryRepository $juryRepository): Response
    {
        return $this->render('jury/index.html.twig', [
            'juries' => $juryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_jury_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,JuryRepository $juryRepository): Response
    {
        $jury = new Jury();
        $form = $this->createForm(JuryType::class, $jury);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $found=$juryRepository->findOneBy([
                'membres'=>$form->get('membres')->getData(),
                'annnee'=>$form->get('annnee')->getData()
            ]);
           // dd($found);
            if($found){
                $this->addFlash('danger', "Le jury :" .$jury->getMembres()->getFullName() ."  a été déjà ajouté pour cette année : ".$form->get('annnee')->getData());


                return $this->redirectToRoute('app_jury_new', [], Response::HTTP_SEE_OTHER);

            } else{

              $entityManager->persist($jury);
              $entityManager->flush();

                $this->addFlash('success', "Le jury :" .$jury->getMembres()->getFullName() ."  a été ajouté ");


                return $this->redirectToRoute('app_jury_index', [], Response::HTTP_SEE_OTHER);


          }


            return $this->redirectToRoute('app_jury_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jury/new.html.twig', [
            'jury' => $jury,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jury_show', methods: ['GET'])]
    public function show(Jury $jury): Response
    {
        return $this->render('jury/show.html.twig', [
            'jury' => $jury,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_jury_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Jury $jury, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JuryType::class, $jury);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_jury_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jury/edit.html.twig', [
            'jury' => $jury,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jury_delete', methods: ['POST'])]
    public function delete(Request $request, Jury $jury, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jury->getId(), $request->request->get('_token'))) {
            $entityManager->remove($jury);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_jury_index', [], Response::HTTP_SEE_OTHER);
    }
}
