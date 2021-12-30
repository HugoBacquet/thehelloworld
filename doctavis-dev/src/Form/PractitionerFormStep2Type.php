<?php

namespace App\Form;

use App\Entity\Practitioner;
use App\Entity\Speciality;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PractitionerFormStep2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pathologies', ChoiceType::class,[
                'choices' => $options["pathologies"],
                'attr' => [
                    'class' => 'js-example-basic-multiple form-select mb-2 bg-light'
                ],
                'multiple' => true,
                'label' => 'Liste des pathologies par catégories'
            ])
            ->add('mainSpecialities', ChoiceType::class,[
                'choices' => $options["mainSpecialities"],
                'multiple' => true,
                'expanded' => true
            ])
            ->add('specialities', ChoiceType::class,[
                'choices' => $options["specialities"],
                'attr' => [
                    'class' => 'js-example-basic-multiple form-select mb-2 bg-light',
                ],
                'label' => 'Spécialités',
                'multiple' => true
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
            'data_class' => null,
            'pathologies' => [],
            'specialities' => [],
            'mainSpecialities' => [],
            'csrf_protection' => false
        ]);
    }
}
