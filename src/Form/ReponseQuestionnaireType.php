<?php

namespace App\Form;

use App\Entity\ReponseQuestionnaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseQuestionnaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // We use an Event Listener to modify the form based on the data (the Questionnaire)
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $reponse = $event->getData(); // This is your ReponseQuestionnaire entity
            $form = $event->getForm();

            // Safety check: if no data or no questionnaire linked, stop
            if (!$reponse || !$reponse->getQuestionnaire()) {
                return;
            }

            $questionnaire = $reponse->getQuestionnaire();

            // ✅ Question 1 (Always exists)
            // We set the 'label' to the actual question text (e.g., "What is your rank?")
            $form->add('rep1', TextType::class, [
                'label' => $questionnaire->getQues1(),
                'required' => true,
                'attr' => ['class' => 'form-control mb-3']
            ]);

            // ✅ Question 2 (Always exists)
            $form->add('rep2', TextType::class, [
                'label' => $questionnaire->getQues2(),
                'required' => true,
                'attr' => ['class' => 'form-control mb-3']
            ]);

            // ✅ Question 3 (Only add if it exists in DB)
            if ($questionnaire->getQues3()) {
                $form->add('rep3', TextType::class, [
                    'label' => $questionnaire->getQues3(),
                    'required' => true,
                    'attr' => ['class' => 'form-control mb-3']
                ]);
            }

            // ✅ Question 4 (Only add if it exists in DB)
            if ($questionnaire->getQues4()) {
                $form->add('rep4', TextType::class, [
                    'label' => $questionnaire->getQues4(),
                    'required' => true,
                    'attr' => ['class' => 'form-control mb-3']
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReponseQuestionnaire::class,
        ]);
    }
}