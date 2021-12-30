<?php

namespace App\Form;

use App\Constant\ConsultationTypes;
use App\Constant\Sector;
use App\Constant\WaitingTime;
use App\Entity\AdditionalCriterion;
use App\Entity\Language;
use App\Entity\Practitioner;
use App\Entity\Temperament;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PractitionerFormStep3Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('languages', EntityType::class, [
                'class' => Language::class,
                'multiple' => true,
                'label' => 'Langues'
            ])
            ->add('thirdPartyPayment', ChoiceType::class, [
                'label' => 'Tiers payant',
                'choices' => [
                    'Non' => 0,
                    'Oui' => 1
                ]
            ])
//            ->add('photo')
//            ->add('photoCabinet')
            ->add('paymentMethods', ChoiceType::class, [
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
                'label' => 'Moyens de paiements'
            ])
            ->add('sector', ChoiceType::class, [
                'label' => 'Prise en charge par la sécurité sociale',
                'choices' => Sector::getHalfSectors()
            ])
            ->add('isCMUAccepted', ChoiceType::class, [
                'label' => 'CMU acceptée',
                'choices' => [
                    'Non' => 0,
                    'Oui' => 1
                ]
            ])
            ->add('consultationTypes', ChoiceType::class, [
                'placeholder' => 'Format de consultation',
                'choices' => ConsultationTypes::getHalfConsultationTypes(),
                'attr' => [
                    'class' => 'js-example-basic-multiple form-select mb-2'
                ],
                "multiple" => true,
                'required' => false
            ])
            ->add('waitingTime', ChoiceType::class, [
                'label' => 'Délai de prise de rendez-vous',
                'choices' => WaitingTime::getWaitingTimes()
            ])
            ->add('additionalCriterions', EntityType::class, [
                'label' => 'Expertise liées aux identités culturelles',
                'class' => AdditionalCriterion::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'js-example-basic-multiple form-select mb-2 bg-light'
                ]
            ])
            ->add('temperaments', EntityType::class, [
                'label' => 'Les adjectifs qui me définissent',
                'class' => Temperament::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'js-example-basic-multiple form-select mb-2 bg-light'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Je complète mon profil'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Practitioner::class,
            'csrf_protection' => false
        ]);
    }
}
