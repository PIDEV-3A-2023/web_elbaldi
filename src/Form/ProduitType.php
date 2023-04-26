<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormInterface;



class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('ref_produit', TextType::class, [
            'label' => 'Référence du produit', 
            'empty_data'=>''
        ])
            ->add('libelle', TextType::class, [
                'empty_data' => '',])
            ->add('description', TextType::class, [
                'empty_data' => '',])
            ->add('image',FileType::class,[
                'mapped'=>false,
                'required'=>false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide (format JPEG ou PNG)',
                    ]),
                    
                ],
               
            ])
            ->add('prixVente')
            ->add('categorie',EntityType::class,[
                'class'=>Categorie::class,
                'choice_label'=>'nomCategorie',
                'placeholder'=>'choisir une catégorie',
                'multiple'=>false,
                'expanded'=>false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une catégorie',
                    ]),
                ],
                
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
