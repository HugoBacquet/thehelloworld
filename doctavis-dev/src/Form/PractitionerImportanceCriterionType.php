<?php

namespace App\Form;

use App\Entity\ImportanceCriterion;
use App\Entity\PractitionerImportanceCriterion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PractitionerImportanceCriterionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note',ChoiceType::class,[
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ],
                'attr' => [
                    'class' => 'star-rating__stars'
                ],
                'choice_attr' => function() {
                    return ['class' => 'star-rating__input'];
                },
                'expanded' => 'true'
            ])
            ->add('criterion', EntityType::class,[
                'class' => ImportanceCriterion::class,
                'label' => 'Critere',
                'disabled' => true,
                'choice_label' => 'name'
            ])
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            [$this, 'onPreSetData']
        );
    }

    public function onPreSetData(FormEvent $event): void {
        $data = $event->getData();
        $form = $event->getForm();

        if (null !== $data->getNote()) {
            $form->remove('note');
            $form->remove('criterion');
        }
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PractitionerImportanceCriterion::class,
        ]);
    }
}
