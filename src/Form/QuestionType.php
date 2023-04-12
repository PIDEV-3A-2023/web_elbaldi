<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Quiz;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('questionn', null, [
                'label' => 'Question : ',
                'label_attr' => ['class' => 'col-sm-3 col-form-label'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('difficulte', null, [
                'label' => 'Difficulté : ',
                'label_attr' => ['class' => 'col-sm-3 col-form-label'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('reponse1', null, [
                'label' => 'Réponse 1 : ',
                'label_attr' => ['class' => 'col-sm-3 col-form-label'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('reponse2', null, [
                'label' => 'Réponse 2 : ',
                'label_attr' => ['class' => 'col-sm-3 col-form-label'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('reponse3', null, [
                'label' => 'Réponse 3 : ',
                'label_attr' => ['class' => 'col-sm-3 col-form-label'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('solution', null, [
                'label' => 'Solution : ',
                'label_attr' => ['class' => 'col-sm-3 col-form-label'],
                'attr' => ['class' => 'form-control']
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
