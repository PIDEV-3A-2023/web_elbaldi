<?php

namespace App\Form;

use App\Entity\Bonplan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BonplanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreBonplan')
            ->add('descriptionBonplan')
            ->add('typeBonplan', ChoiceType::class, [
                'choices' => [
                    '' => '',
                    'Hotel' => 'hotel',
                    'Restaurant' => 'restaurant',
                ],
            ])
            
            ->add('imageBonplan', FileType::class, [
                'label' => 'choisir une image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG or PNG)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bonplan::class,
        ]);
    }
}
