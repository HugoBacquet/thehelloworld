<?php

namespace App\Form;

use App\Constant\Sex;
use App\Entity\AccessibilityCriterion;
use App\Entity\AdditionalCriterion;
use App\Entity\Equipment;
use App\Entity\Formation;
use App\Entity\Language;
use App\Entity\Practitioner;
use App\Entity\Speciality;
use App\Entity\Temperament;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;

class   PractitionerFormStep1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => 'Prénoms'
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => 'Adresse postal',
                    'required' => true
                ]
            ])
            ->add('postalCode', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => 'Code postal'
                ]
            ])
            ->add('phoneNumber',TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2 bg-light',
                    'placeholder' => 'Numéro de téléphone'
                ],
                'required' => false
            ])
            ->add('website', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2 bg-light',
                    'placeholder' => 'Lien internet'
                ],
                'label' => 'Où peut-on prendre rendez-vous ?',
                'required' => false
            ])
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2 bg-light',
                    'placeholder' => 'Adresse mail'
                ]
            ])
            ->add('sex',ChoiceType::class, [
                'label' => 'Sexe',
                'choices' => Sex::getHalfSexes(),
                'attr' => [
                    'class' => 'form-select mb-2'
                ],
                'expanded' => true
            ])
            ->add('doctavisNews', CheckboxType::class, [
                'label' => "J'accepte de recevoir par courrier électronique des sollicitations de l'équipe de Doctavis",
                'required' => false,
                'attr' => [
                    'class' => 'form-checkbox mb-2'
                ],
            ])
            ->add('associatesNews', CheckboxType::class, [
                'label' => "J'accepte de recevoir par courrier électronique des sollicitations de nos partenaires commerciaux ou d'associations",
                'required' => false,
                'attr' => [
                    'class' => 'form-checkbox mb-2'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Valider'
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
