<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\CategorieCandidats;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('categorie')
            ->add('categorie', EntityType::class, [
                'class' => CategorieCandidats::class,
                'choice_label' => 'name',
                'required' => true,

                'attr'=>[
                    'class'=>'form-control'
                ]

            ])
            ->add('mom')
            ->add('prenom')
            ->add('numeroCandidat')


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}
