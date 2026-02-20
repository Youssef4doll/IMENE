<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\User;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\QuestionnaireAgentRepository;


class AgentType extends AbstractType
{
    public function __construct(
        private QuestionnaireAgentRepository $questionnaireAgentRepository
    ) {}
    
public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $games = $this->questionnaireAgentRepository->findDistinctGames();

    // label => value (since it's varchar, keep same string)
    $games = $this->questionnaireAgentRepository->findDistinctGames();
    $gameChoices = array_combine($games, $games);

    $builder
        ->add('pseudo')
        ->add('game', ChoiceType::class, [
            'choices' => $gameChoices,
            'placeholder' => 'Choose a game',
            'required' => true,
        ])
        ->add('player', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'username',
            'placeholder' => 'Select a player',
            'label' => 'Player',
        ])
        ->add('team', EntityType::class, [
            'class' => Team::class,
            'choice_label' => 'name',
            'required' => false,
            'placeholder' => 'No Team (Free Agent)',
            'label' => 'Team',
        ])
        ->add('rank')
        ->add('status', ChoiceType::class, [
            'choices' => [
                'Active' => 'active',
                'Inactive' => 'inactive',
                'Banned' => 'banned',
            ],
            'placeholder' => 'Select status',
        ])
        ->add('socialsLink');
}

}
