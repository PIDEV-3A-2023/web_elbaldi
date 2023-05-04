<?php

namespace App\Form;
use App\Entity\Client;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Participation;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
class ParticipationType extends AbstractType
{private  $entityManager;
    private $urlGenerator;
    private $requestStack;

    public function __construct(
        EntityManagerInterface $entityManager, 
        UrlGeneratorInterface $urlGenerator,
        RequestStack $requestStack)
     {
        
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
        $request = $this->requestStack->getCurrentRequest();
        $clientid = $request->attributes->get('idEvent');
        $this->entityManager=$entityManager
        ->getRepository(Event::class)
        ->find($clientid);
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { 
       
        $builder
        
            ->add('nomClient',HiddenType::class,[
                'data' => $options['client']->getNom(),
            ])
            ->add('prenomClient',HiddenType::class,[
                'data' => $options['client']->getPrenom(),])
            ->add('nomEvenement',HiddenType::class,[
                'data' => $options['event']->getTitleEvent(),
            ])
            ->add('date',DateTimeType::class,[
                'data' => $options['event']->getTimeEvent(),
            ])
            
            ->add('idEvenement',EntityType::class,['class' =>Event::class,
           
            'data' => $this->entityManager,
            'choice_value' => 'idEvent',
             ])
             
            ->add('idClient',EntityType::class,['class' =>Client::class,
            'choice_label' => 'idClient',
            'choice_value' => 'idClient',

                ])
           
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
            'event' => null,
            'client'=> null,
        ]);
    }
}
