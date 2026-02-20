<?php

namespace App\Form;

use App\Entity\Agent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // Assuming you use Choice or Text
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo / Gamer Tag'
            ])
            
            ->add('game', TextType::class, [ 
                'disabled' => true, // <--- THIS PREVENTS EDITING
                'label' => 'Game (Cannot be changed)',
                'attr' => [
                    'style' => 'background-color: #2a2a2a; color: #888; cursor: not-allowed;' 
                ]
            ])

            ->add('rank', TextType::class, [
                'label' => 'Current Rank'
            ])
            
            ->add('socialsLink', TextType::class, [
                'label' => 'Social Link (Optional)',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}