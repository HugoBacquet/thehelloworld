<?php

namespace App\Form;

use App\Constant\Sex;
use App\Entity\AccessibilityCriterion;
use App\Entity\AdditionalCriterion;
use App\Entity\Equipment;
use App\Entity\Formation;
use App\Entity\Pathology;
use App\Entity\Practitioner;
//use App\Entity\Profession;
use App\Entity\Speciality;
use App\Entity\Temperament;
use App\Repository\PathologyRepository;
use App\Repository\SpecialityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;

class PractitionerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2'
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2'
                ]
            ])
            ->add('postalCode', IntegerType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'class' => 'form-control mb-2'
                ],
                'required' => false
            ])
            ->add('sex', ChoiceType::class, [
                'label' => 'Est-ce une femme ou un homme ?',
                'choices' => Sex::getHalfSexes(),
                'attr' => [
                    'class' => 'form-select mb-2'
                ],
                'expanded' => true
            ])
            ->add('specialities', EntityType::class, [
                'class' => Speciality::class,
                'label' => 'Mon praticien est un...',
                'query_builder' => function(SpecialityRepository  $repository) {
                    return $repository->createQueryBuilder('s')
                        ->where('s.level = 1')
                        ->orderBy("s.parent", "ASC");
                },
                'placeholder' => 'Speciality',
                'choice_label' => 'name',
                'required' => false,
                'expanded' =>  true,
                'multiple' => true
            ])
            ->add('unmappedPathologies', ChoiceType::class,[
                'choices' => $options["pathologies"],
                'attr' => [
                    'class' => 'js-example-basic-multiple form-select mb-2 bg-light'
                ],
                'multiple' => true,
                'mapped' => false,
                'label' => 'Quel type de pathologies soigne-t-il ?'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Practitioner::class,
            'csrf_protection' => false,
            'practitioner' => false,
            'pathologies' => []
        ]);
    }
}
