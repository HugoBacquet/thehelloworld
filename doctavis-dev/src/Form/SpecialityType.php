<?php

namespace App\Form;

use App\Entity\Speciality;
use App\Repository\SpecialityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('specialities', ChoiceType::class,[
                'choices' => $options["specialities"],
                'multiple' => true
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Poursuivre la recherche'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'specialities' => []
        ]);
    }
}
