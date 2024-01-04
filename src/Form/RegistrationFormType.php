<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Saisir votre nom'],
            ])
            ->add('prenoms', TextType::class, [
                'label' => 'Prénoms',
                'attr' => ['placeholder' => 'Saisir vos prénoms'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Saisir votre email'],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr'=>['placeholder' => 'Saisir votre mot de passe']
            ])
            ->add('passwordConfirm', PasswordType::class, [
                'label' => 'Confirmer le mot de passe',
                'attr'=>['placeholder' => 'Confirmer votre mot de passe']
            ])
            ->add('img', FileType::class, [
                'label' => 'Uploader l\'image de l\'utilisateur',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG ou PNG.',
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle de l\'utilisateur:',
                'multiple' => true,
                'choices' => [
                    'ROLE_ADMINISTRATEUR' => 'ROLE_ADMINISTRATEUR',
                    'ROLE_MEMBRE_JURY' => 'ROLE_MEMBRE_JURY',
                    'ROLE_MEMBRE_BUREAU' => 'ROLE_MEMBRE_BUREAU',
                ]

            ]);
//            ->add('email',EmailType::class,[
//                'attr' => [
//                    'placeholder' => 'Saisir votre mail',
//        '           label'=>"Email"
//    ]
//            ])
//            ->add('nom',TextType::class,[
//                'attr'=>[
//                    'label'=>"Nom",
//                    'placeholder'=>'Saisir votre nom'
//                ]
//            ])
//            ->add('prenoms',TextType::class,[
//                'attr'=>[
//                    'label'=>"Prénoms",
//                    'placeholder'=>'Saisir votre prénolm'
//                ]
//            ])
//            ->add('img', FileType::class, [
//                'label' => 'Uploader l\'image de l\'utilisateur',
//                'multiple' => false,
//                'mapped' => false,
//                'required' => false,
//                'constraints' => [
//                    new File([
//                        'mimeTypes' => [
//                            'image/jpeg',
//                            'image/png',
//                        ],
//                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG ou PNG.',
//                    ]),
//                ],
//            ])
//            ->add('password', PasswordType::class,[
//                'label'=>"Mot de Passe"
//            ])
//            ->add('passwordConfirm',PasswordType::class)
//            ->add('roles',ChoiceType::class,[
//                'multiple' => true,
//                'choices' => [
//                    'ROLE_ADMINISTRATEUR' => 'ROLE_ADMINISTRATEUR',
//                    'ROLE_MEMBRE_JURY' => 'ROLE_MEMBRE_JURY',
//                    'ROLE_MEMBRE_BUREAU' => 'ROLE_MEMBRE_BUREAU',
//                ],
//                'label'=>'Rôle de l\'utilisateur'
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
