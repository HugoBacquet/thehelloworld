<?php

namespace App\Form;

use App\Entity\Equipment;
//use App\Entity\Profession;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class HomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pathologies', ChoiceType::class,[
                'choices' => $options["pathologies"],
                'multiple' => true,
                'required' => false
            ])
            ->add('specialities', ChoiceType::class,[
                'choices' => $options["specialities"],
                'multiple' => true,
                'expanded' => true
            ])
            ->add('location', TextType::class,[
                'attr' => [
                    'placeholder' => 'Mon code postal',
                    'class' => 'form-control'
                ],
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{5}$/',
                        'message' => 'Le code postal est obligatoire et doit être composé de 5 chiffres',
                    ])]
            ])
            ->add('rayon', ChoiceType::class,[
                'choices' => [
                    '10km' => 10,
                    '20km' => 20,
                    '50km' => 50,
                ],
                'choice_attr' => [
                    '10km' => ['class' => 'ms-3 me-2'],
                    '20km' => ['class' => 'ms-3 me-2'],
                    '50km' => ['class' => 'ms-3 me-2']
                ],
                'expanded' => true,
                'label' => 'Je peux me déplacer jusqu\'à :'
            ])
            ->add('submit', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-lg btn-outline-secondary my-3'
                ],
                'label' => 'Lancer la recherche'
            ])
            ->add('partialSubmit', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-light'
                ],
                'label' => 'Lancer la recherche'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'pathologies' => [],
            'specialities' => [],
        ]);
    }
}
