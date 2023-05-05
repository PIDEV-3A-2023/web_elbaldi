<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder



            ->add('nom', null, [
                'label' => 'Nom',
            ])
            ->add('prenom', null, [
                'label' => 'Prénom',
            ])
            ->add('numtel', null, [
                'label' => 'Numéro de téléphone',
            ])

            ->add('ville')
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier le profil ',
                'attr' => ['class' => 'btn btn-success custom-btn'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
