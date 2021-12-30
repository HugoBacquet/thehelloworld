<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Type;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Adresse mail'
                ]
            ])
            ->add('doctavisNews', CheckboxType::class, [
                'label' => "J'accepte de recevoir par courrier électronique des sollicitations de l'équipe de Doctavis",
                'required' => false,
                'attr' => [
                    'class' => 'm-1'
                ]
            ])
            ->add('associatesNews', CheckboxType::class, [
                'label' => "J'accepte de recevoir par courrier électronique des sollicitations de nos partenaires commerciaux ou d'associations",
                'required' => false,
                'attr' => [
                    'class' => 'm-1'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Confirmer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
