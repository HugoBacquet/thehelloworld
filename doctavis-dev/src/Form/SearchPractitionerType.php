<?php

namespace App\Form;

use App\Constant\ConsultationTypes;
use App\Constant\Sector;
use App\Constant\Sex;
use App\Constant\WaitingTime;
use App\Entity\AdditionalCriterion;
use App\Entity\Language;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchPractitionerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language', EntityType::class,[
                'label' => 'Langues parlées',
                'class' => Language::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('sex', ChoiceType::class,[
                'label' => 'Sexe',
                'choices' => Sex::getSexes()
            ])
            ->add('isPayingTiers', ChoiceType::class,[
                'label' => 'Tiers payant',
                'choices' => [
                    'Non' => 0,
                    'Oui' => 1
                ]
            ])
            ->add('paymentMethods', ChoiceType::class,[
                'choices' => [
                    'espèces' => 'espèces',
                    'chèques' => 'chèques',
                    'CB' => 'CB',
                    'Autres' => 'Autres'
                ],
                'multiple' => true,
                'attr' => [
                    'class' => 'js-example-basic-multiple form-select mb-2'
                ],
                'label' => 'Moyens de paiements '
            ])
            ->add('isCMUAccepted', ChoiceType::class,[
                'label' => 'CMU acceptée',
                'choices' => [
                    'Non' => 0,
                    'Oui' => 1
                ]
            ])
            ->add('sector', ChoiceType::class,[
                'label' => 'Prise en charge par la sécurité sociale',
                'choices' => Sector::getSectors()
            ])
            ->add('waitingTime', ChoiceType::class,[
                'label' =>'Délai de prise de rendez-vous',
                'choices' => WaitingTime::getWaitingTimes()
            ])
            ->add('consultationType', ChoiceType::class,[
                'label' => 'Format de consultation',
                'choices' => ConsultationTypes::getConsultationTypes(),
                'multiple' => true

            ])
            ->add('additionalCriterions', EntityType::class, [
                'label' => 'Expertise liées aux identités culturelles',
                'class' => AdditionalCriterion::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'js-example-basic-multiple form-select mb-2'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ])
            ->add('submitAdditionnal', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
