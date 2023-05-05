<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Reservation;
use App\Entity\Utilisateur;
use App\Entity\Bonplan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                    ->add('nombrePersonnes', null, [
                        'attr' => ['class' => 'my-custom-class']
                    ])
        
            ->add('dateReservation')

             ->add('statutReservation', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'En attente',
                    'confirmée' => 'confirmée',
                ],
            ])     
            


            ->add('idUser',EntityType::class,[
                'class'=>Utilisateur::class,
                'choice_label'=>FALSE,
                'multiple'=>false,
                'expanded'=>false,
                'empty_data'=>'',
                'attr' => ['hidden' => true],
                ])


            ->add('idBonplan', EntityType::class, [
                'class' => Bonplan::class,
                'label' => false,

               
                'attr' => ['hidden' => true]
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'idBonplan' => bonplan::class,

        ]);

    }
}