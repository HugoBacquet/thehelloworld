<?php

namespace App\Form;

use App\Entity\Note;
use App\Entity\Practitioner;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('practitioner', ChoiceType::class,[
                'label' => 'Praticien',
                'choices' => $options['practitioners'],
                'multiple' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Je recommande mon praticien'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'practitioners' => [],
          //  'data_class' => Note::class,
        ]);
    }
}
