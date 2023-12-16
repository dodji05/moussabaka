<?php

namespace App\Form;

use App\Entity\Sourate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SourateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('surahnumber')
            ->add('surahnamearabic')
            ->add('surahnameenglish')
            ->add('ayahnumber')
            ->add('originalarabictext')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sourate::class,
        ]);
    }
}
