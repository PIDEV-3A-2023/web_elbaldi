<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom_categorie', null, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Nom de categorie :',
            'label_attr' => ['class' => 'col-sm-2 col-form-label']

        ])
        ->add('description', null, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Description :',
            'label_attr' => ['class' => 'col-sm-2 col-form-label'],

        ])
        
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
