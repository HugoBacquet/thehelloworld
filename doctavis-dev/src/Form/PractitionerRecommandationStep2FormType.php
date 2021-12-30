<?php

namespace App\Form;

use App\Constant\ConsultationTypes;
use App\Constant\Sector;
use App\Constant\Sex;
use App\Constant\WaitingTime;
use App\Entity\Formation;
use App\Entity\Language;
use App\Entity\Practitioner;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PractitionerRecommandationStep2FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options["accessibilityCriterions"] as $key => $accessibilityCriterion) {
            $multiple = $key === "ASCENSEUR" || $key === "ACCESSIBLE EN TRANSPORT EN COMMUN" ? false : true;
            $builder
                ->add(self::slugify($key), ChoiceType::class, [
                    'label' => $key,
                    'mapped' => false,
                    'required' => false,
                    'choices' => $accessibilityCriterion,
                    'multiple' => $multiple
                ]);
        }
        $builder
            ->add('equipments', EquipmentType::class, [
                'label' => 'Equipement',
                'required' => 'false',
                'mapped' => false
            ])
            ->add('languages', EntityType::class, [
                'label' => 'Langues parlées',
                'class' => Language::class,
                'choice_label' => 'name',
                'multiple' => 'true'
            ])
            ->add('sex', ChoiceType::class, [
                'label' => 'Sexe',
                'choices' => Sex::getHalfSexes()
            ])
            ->add('website', TextType::class, [
                'label' => 'Où peut-on prendre rendez-vous ?',
                'attr' => [
                    'placeholder' => 'Lien Internet'
                ],
                'required' => false
            ])
            ->add('thirdPartyPayment', ChoiceType::class, [
                'label' => 'Tiers payant',
                'choices' => [
                    'Non' => 0,
                    'Oui' => 1
                ]
            ])
            ->add('paymentMethods', ChoiceType::class, [
                'choices' => [
                    'espèces' => 'espèces',
                    'chèques' => 'chèques',
                    'CB' => 'CB',
                    'Autres' => 'Autres'
                ],
                'multiple' => true,
                'attr' => [
                    'class' => 'js-example-basic-multiple form-select mb-2 bg-light'
                ],
                'label' => 'Moyens de paiements '
            ])
            ->add('sector', ChoiceType::class, [
                'label' => 'Prise en charge',
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
                'label' => 'Format de consultation',
                'choices' => ConsultationTypes::getHalfConsultationTypes(),
                'attr' => [
                    'class' => 'js-example-basic-multiple form-select mb-2'
                ],
                "multiple" => true,
                'required' => false
            ])
            ->add('practitionerImportanceCriterions', CollectionType::class, [
                'entry_type' => PractitionerImportanceCriterionType::class,
                'label' => 'Recommandation par thématiques'
            ])
            ->add('waitingTime', ChoiceType::class, [
                'label' => 'Délai de prise de rendez-vous',
                'choices' => WaitingTime::getWaitingTimes()
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Practitioner::class,
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
