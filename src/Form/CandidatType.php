<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\CategorieCandidats;
use App\Entity\EcoledeProvenance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('categorie')

            ->add('mom')
            ->add('prenom')
            ->add('numeroCandidat')
            ->add('age')
            ->add('localite')
            ->add('img', FileType::class, [
                'label' => 'Uploadez une photo du candidat',
                'multiple' => false,
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k', // Limite la taille du fichier à 1 Mo
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG ou PNG valide.',
                    ]),

            ]])
            ->add('EcoledeProvenance',EntityType::class,[
                'class'=>EcoledeProvenance::class,
                'choice_label' => 'nom',
                'required' => true,

                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('categorie', EntityType::class, [
                'class' => CategorieCandidats::class,
                'choice_label' => 'name',
                'required' => true,

                'attr'=>[
                    'class'=>'form-control'
                ]

            ])
            ->add('active')



        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}
