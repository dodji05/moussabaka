<?php

namespace App\Form;

use App\Entity\Annee;
use App\Entity\Jury;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JuryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('membres', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE :val')
                        ->setParameter('val', '%ROLE_MEMBRE_JURY%')
                        ->orderBy('u.nom', 'ASC');
                }
            ])
            ->add('annnee',EntityType::class,[
                'class'=>Annee::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Jury::class,
        ]);
    }
}
