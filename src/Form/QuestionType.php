<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


use App\Entity\Quiz;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder

            ->add('questionn', null, [
                'label' => 'Question : ',
                'label_attr' => ['class' => 'col-sm-3 col-form-label'],
                'attr' => ['class' => 'form-control'],
                'empty_data' => ''
            ])
            ->add('difficulte', ChoiceType::class, [
                'choices'  => [
                    '' => '',
                    'Facile' => 'Facile',
                    'Moyenne' => 'Moyenne',
                    'Difficile' => 'Difficile',
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Difficulté :',
                'empty_data' => ''
            ])
            ->add('reponse1', null, [
                'label' => 'Réponse 1 : ',
                'label_attr' => ['class' => 'col-sm-3 col-form-label'],
                'attr' => ['class' => 'form-control'],
                'empty_data' => ''

            ])
            ->add('reponse2', null, [
                'label' => 'Réponse 2 : ',
                'label_attr' => ['class' => 'col-sm-3 col-form-label'],
                'attr' => ['class' => 'form-control'],
                'empty_data' => ''

            ])
            ->add('reponse3', null, [
                'label' => 'Réponse 3 : ',
                'label_attr' => ['class' => 'col-sm-3 col-form-label'],
                'attr' => ['class' => 'form-control'],
                'empty_data' => ''

            ])
            ->add('solution', null, [
                'label' => 'Solution : ',
                'label_attr' => ['class' => 'col-sm-3 col-form-label'],
                'attr' => ['class' => 'form-control'],
                'empty_data' => ''
            ])



            ->add('idQuiz', EntityType::class, [
                'class' => Quiz::class,
                'choice_label' => 'nom',

                'label' => 'Id_Quiz : ',
                'label_attr' => ['class' => 'col-sm-3 col-form-choice_label'],
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
