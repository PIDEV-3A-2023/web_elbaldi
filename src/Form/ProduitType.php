<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('ref_produit', TextType::class, [
            'label' => 'Référence du produit'
        ])
            ->add('libelle')
            ->add('description')
            ->add('image',FileType::class,[
                'mapped'=>false,
                'required'=>false,
            ])
            ->add('prixVente')
            ->add('categorie',EntityType::class,[
                'class'=>Categorie::class,
                'choice_label'=>'nomCategorie',
                'placeholder'=>'choisir une categorie',
                'multiple'=>false,
                'expanded'=>false,
                
                ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
