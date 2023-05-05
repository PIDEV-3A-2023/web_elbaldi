<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titleEvent', null, [
                'label' => "Titre de l'événement"
            ])
            ->add('imageEvent', FileType::class, ['data_class' => null, 'label' => 'Image', 'required' => false])

            ->add('descriptionEvent', null, [
                'label' => "Description de l'événement"
            ])

            ->add('timeEvent', DateTimeType::class, [
                'label' => "Heure de l'événement",
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'Please select futur time and date'
                    ])
                ]
            ])
            ->add('Organisation');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
