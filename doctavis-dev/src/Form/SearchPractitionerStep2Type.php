<?php

namespace App\Form;

use App\Entity\Equipment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchPractitionerStep2Type extends AbstractType
{
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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options["accessibilityCriterions"] as $key => $accessibilityCriterion) {
            $multiple = $key === "ASCENSEUR" || $key === "ACCESSIBLE EN TRANSPORT EN COMMUN" ? false : true;
            $builder
                ->add(self::slugify($key), ChoiceType::class, [
                    'label' => $key,
                    'choices' => $accessibilityCriterion,
                    'multiple' => $multiple,
                    'required' => false
                ]);
        }
        $builder
            ->add('equipments', EntityType::class, [
                'class' => Equipment::class,
                'choice_label' => 'name',
                'label' => 'Equipement',
                'required' => 'false'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-lg btn-outline-secondary my-3'
                ]
            ])
            ->add('submit_return', SubmitType::class, [
                'label' => 'Retour aux critères globaux'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'accessibilityCriterions' => []
        ]);
    }
}
