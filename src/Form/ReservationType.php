<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Utilisateur;
use App\Entity\Bonplan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombrePersonnes')
            ->add('dateReservation')
            ->add('statutReservation', ChoiceType::class, [
                'choices' => [
                    '' => '',
                    'Confirmée' => 'confirmée',
                    'Annulée' => 'annulée',
                ],
            ])
            ->add('idUser', EntityType::class,['class'=>Utilisateur::class,'choice_label'=>'idUser',])
            ->add('idBonplan', EntityType::class,['class'=>Bonplan::class,'choice_label'=>'idBonplan',])
            //->add('idBonplan')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
