<?php

namespace App\Form;

use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu')
            ->add('date_comm')
            ->add('produit',EntityType::class,[
                'class'=>Produit::class,
                'choice_label'=>'ref_produit',
                'multiple'=>false,
                'expanded'=>false,
                ])
          
        
        ->add('user',EntityType::class,[
            'class'=>Utilisateur::class,
            'choice_label'=>'nom',
            'multiple'=>false,
            'expanded'=>false,
            ])
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
