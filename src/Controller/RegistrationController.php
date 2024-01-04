<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\FileUploader;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(MailerInterface $mailer,FileUploader $fileUploader,Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $oldPhoto = $user->getPhoto();

            if ($form->get('img')->getData()) {
                // Supprimer l'ancienne photo
                if ($oldPhoto) {
                    $fileUploader->remove($oldPhoto);
                }
                $image = $form->get('img')->getData();
                $fichier = $fileUploader->upload($image);
                $user->setPhoto($fichier);
            }

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', "L'utilisateur :" .$user->getFullName() ."  a été ajouté avec succès ");
            //toastr()->addSuccess("L'utilisateur :" .$user->getFullName() ."  a été ajouté avec succès ");
            $email = (new TemplatedEmail())
                ->from( new Address('moussabaka@openkanz.com','Plateforme Moussabaka'))
                ->to(new Address($user->getEmail()))
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Création de compte utilisateur de : ' . $user->getFullName())
                ->priority(Email::PRIORITY_HIGH)

                // path of the Twig template to render
                ->htmlTemplate('registration/confirmation_email.html.twig')

                // pass variables (name => value) to the template
                ->context([

                    'user' => $user,
                    'password'=>$form->get('password')->getData()
                ]);

            $mailer->send($email);




            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
