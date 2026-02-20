<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\User;
use App\Entity\Team;
use App\Repository\QuestionnaireAgentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FrontAgentCreateType extends AbstractType
{
    public function __construct(private QuestionnaireAgentRepository $questionnaireRepo)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // ✅ Get ALL distinct games from questionnaire_agent
        $games = $this->questionnaireRepo->findDistinctGames();

        // ✅ label => value (strings)
        $gameChoices = array_combine($games, $games);

        $builder
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
            ->add('pseudo')
            ->add('game', ChoiceType::class, [
                'choices' => $gameChoices,
                'placeholder' => 'Choose a game',
                'required' => true,
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
