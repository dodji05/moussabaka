<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $builder
//            ->add('email')
//            ->add('roles')
//            ->add('password')
//            ->add('photo')
//            ->add('nom')
//            ->add('prenoms')
//            ->add('isActif')
//        ;
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

            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle de l\'utilisateur:',
                'multiple' => true,
                'choices' => [
                    'ROLE_ADMINISTRATEUR' => 'ROLE_ADMINISTRATEUR',
                    'ROLE_MEMBRE_JURY' => 'ROLE_MEMBRE_JURY',
                    'ROLE_MEMBRE_BUREAU' => 'ROLE_MEMBRE_BUREAU',
                ]

            ])
            ->add('isActif')


        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
