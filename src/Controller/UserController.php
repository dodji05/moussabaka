<?php

namespace App\Controller;

use App\Entity\PasswordUpdateProfile;
use App\Entity\PaswordUpdate;
use App\Entity\User;
use App\Form\PasswordUpdateType;
use App\Form\PasswordUpdateUserType;
use App\Form\UpdateUserFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
       // dd($user);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash(
                'success',
                "le profil de  " . ' ' . $user->getFullName() . " a bien été mis à jour !"
            );

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig',[
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/account/password-update/by-user', name: 'account_password_user', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER')")]
    public function updatePasswordByUser(Request $request, UserPasswordHasherInterface $encoder, UserRepository $userRepository, EntityManagerInterface $manager)
    {
        $passwordUpdate = new PasswordUpdateProfile();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateUserType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. Vérifier que le oldPassword du formulaire soit le même que le password de l'user
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
                // Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->hashPassword($user, $newPassword);

                $user->setPassword($hash);
                $manager->persist($user);
                $manager->flush();

               // $userRepository->save($user, true);

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );

                return $this->redirectToRoute('app_logout');
            }
        }


        return $this->render('user/passwordupdatebyuser.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/account/password-update', name: 'reset_password_by_admin', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_ADMINISTRATEUR')")]
    public function updatePassword(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $encoder, EntityManagerInterface $manager)
    {
        $passwordUpdate = new PaswordUpdate();
        $user = $userRepository->findOneBy([
            'id' => $user->getId()
        ]);

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $encoder->hashPassword(
                    $user,
                    $form->get('newPassword')->getData()
                )
            );

            $newPassword = $passwordUpdate->getNewPassword();
            $hash = $encoder->hashPassword($user, $newPassword);

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "le mot de passe de " . ' ' . $user->getFullName() . " a bien été reinitialisé !"
            );

            return $this->redirectToRoute('app_user_index');

        }


        return $this->render('user/passwordupdatebyAdmin.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/account/profile-update', name: 'account_profile_update_by_user', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER')")]
    public function profile(Request $request, EntityManagerInterface $manager,FileUploader $fileUploader, UserRepository $userRepository, UserPasswordHasherInterface $encoder)
    {
        $user = $this->getUser();
        // dd($user);
        $passwordUpdate = new PasswordUpdateProfile();


        // dd($user);

        $form = $this->createForm(UpdateUserFormType::class, $user);
        $form_reset = $this->createForm(PasswordUpdateUserType::class);

        $form->handleRequest($request);
        // Mise à jour photo


        //fin
        ///dd($fichier,$user);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('img')->getData()) {

                $image = $form->get('img')->getData();
                $fichier = $fileUploader->upload($image);
                $user->setPhoto($fichier);
                $manager->persist($user);
            }
            // dd($user);
            $manager->flush();
            // $userRepository->save($user, true);
            $this->addFlash(
                'success',
                "Les données du profil ont été modifiées avec succès !"
            );

            return $this->redirectToRoute('account_profile_update_by_user');

        }



        $form_reset->handleRequest($request);
        if ($form_reset->isSubmitted() && $form_reset->isValid()) {

            //dd($form_reset->get('oldPassword'));
            // 1. Vérifier que le oldPassword du formulaire soit le même que le password de l'user
            if (!password_verify($form_reset->get('oldPassword')->getData(), $user->getPassword())) {


                //dd(!password_verify($passwordUpdate->getOldPassword(), $user->getPassword()));
                // Gérer l'erreur
                $form_reset->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            } else {
                $newPassword = $form_reset->get('newPassword')->getData();
                $hash = $encoder->hashPassword($user, $newPassword);

                $user->setPassword($hash);
                if ($user->getPasswordChangeRequired() == true) {
                    $user->setPasswordChangeRequired(false);
                }
                $user->setPasswordChangedAt(new \DateTime());


                $userRepository->save($user, true);


                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );


                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('user/update-profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'form_reset' => $form_reset->createView(),

        ]);


    }
}
