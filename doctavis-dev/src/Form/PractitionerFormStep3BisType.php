<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Practitioner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PractitionerFormStep3BisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options["accessibilityCriterions"] as $key => $accessibilityCriterion) {
            $multiple = $key === "ASCENSEUR" || $key === "ACCESSIBLE EN TRANSPORT EN COMMUN" ? false : true;
            $builder
                ->add(self::slugify($key), ChoiceType::class, [
                    'label' => $key,
                    'mapped' => false,
                    'choices' => $accessibilityCriterion,
                    'multiple' => $multiple,
                    'required' => false
                ]);
        }
        $builder
            ->add('equipments', TextType::class, [
                'label' => 'Equipement',
                'required' => 'false',
                'mapped' => false,
                'attr' =>  [
                    'placeholder' => 'Equipement 1;Equipement 2;....',
                    'class' => 'form-control'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-lg btn-outline-secondary my-3'
                ],
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'csrf_protection' => false,
            'accessibilityCriterions' => []
        ]);
    }

    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '_', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '_');
        // remove duplicate _
        $text = preg_replace('~-+~', '_', $text);
        // lowercase
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
