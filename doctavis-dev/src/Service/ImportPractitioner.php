<?php

namespace App\Service;

use App\Constant\Sector;
use App\Constant\Sex;
use App\Entity\AccessibilityCriterion;
use App\Entity\AdditionalCriterion;
use App\Entity\Equipment;
use App\Entity\Formation;
use App\Entity\Language;
use App\Entity\Pathology;
use App\Entity\Practitioner;
//use App\Entity\Profession;
use App\Entity\Speciality;
use App\Entity\Temperament;
use App\Form\PractitionerFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ImportPractitioner
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    private $formFactory;

    public function __construct(EntityManagerInterface $manager, FormFactoryInterface $formFactory)
    {
        $this->manager = $manager;
        $this->formFactory =$formFactory;
    }

    /**
     * @param UploadedFile $file
     * @return string[]
     */
    public function import(UploadedFile $file, $request)
    {
        try {
            $response = [
                "success" => "",
                "warning" => "",
                "error" => ""
            ];
            $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder(), new JsonEncoder()]);
            $csvLines = $serializer->decode(file_get_contents($file), 'csv', [CsvEncoder::DELIMITER_KEY=>';']);
            if (empty($csvLines)) {
                $response["error"] = "Bad File";
                return $response;
            }

//            $professions = [];
            foreach ($csvLines as $csvLine) {
                    /** @var Practitioner */
                    $practitioner = new Practitioner();
//                    $professionName = $this->decode($csvLine['profession']);
//                    $profession = $this->manager->getRepository(Profession::class)->findOneBy(['name' =>  $professionName]);
//                    if (null === $profession) {
//                        if(!array_key_exists($professionName, $professions)) {
//                            $profession = new Profession();
//                            $profession->setName($professionName);
//                            $this->manager->persist($profession);
//                            $professions[$professionName] = $profession;
//                        } else {
//                            $profession = $professions[$professionName];
//                        }
//                    }
                    $data = [
                        'firstname' => $this->decode($csvLine['firstname']),
                        'lastname' => $this->decode($csvLine['lastname']),
                        'sex' => (int)$this->decode($csvLine['sex']),
                        'phoneNumber' => $this->decode($csvLine['phoneNumber']),
                        'address' => $this->decode($csvLine['address']),
                        'sector' => (int)$this->decode($csvLine['sector']),
                        'website' => $this->decode($csvLine['website']),
                        'city' => $this->decode($csvLine['city']),
                        'postalCode' => (int)$this->decode($csvLine['postalCode']),
                        'isVitalCardAccepted' => $csvLine['isVitalCardAccepted'] ? $this->decode($csvLine['isVitalCardAccepted']) == 'Oui' : null,
                        'isCMUAccepted' => $csvLine['isCMUAccepted'] ? $this->decode($csvLine['isCMUAccepted']) == 'Oui' : null,
                        'thirdPartyPayment' => $csvLine['thirdPartyPayment'] ? $this->decode($csvLine['thirdPartyPayment']) == 'Oui' : null,
                        'email' => $this->decode($csvLine['email']),
                        'immatriculation' => $this->decode($csvLine['immatriculation']),

                        'experience' => $this->decode($csvLine['experience']),
                        'dateOfBirth' => $csvLine['dateOfBirth'] !== ""?(new \DateTime($this->decode($csvLine['dateOfBirth']))):null,
                        'placeOfBirth' => $this->decode($csvLine['placeOfBirth']),
                        'waitingTime' => $this->decode($csvLine['waitingTime']),
                        'isEmergencyAccepted' => $this->decode($csvLine['isEmergencyAccepted']),

                        'public' => $this->stringToArray($this->decode($csvLine['public'])),
                        'paymentMethods' => $this->stringToArray($this->decode($csvLine['paymentMethods'])),
                        'consultationTypes' => $this->stringToArray($this->decode($csvLine['consultationTypes'])),

                        'languages' => $this->stringToArray($this->decode($csvLine['languages'])),
                        'pathologies' => $this->stringToArray($this->decode($csvLine['pathologies'])),
                        'specialities' => $this->stringToArray($this->decode($csvLine['specialities'])),
                        'equipments' => $this->stringToArray($this->decode($csvLine['equipments'])),
                        'accessibilityCriterions' => $this->stringToArray($this->decode($csvLine['accessibilityCriterions'])),
                        'temperaments' => $this->stringToArray($this->decode($csvLine['temperaments'])),
                        'additionalCriterions' => $this->stringToArray($this->decode($csvLine['additionalCriterions']))
                    ];
                    $practitioner->setAddress($data['address']);
                    $practitioner->setFirstname($data['firstname']);
                    $practitioner->setLastname($data['lastname']);
                    $practitioner->setSex($data['sex']);
                    $practitioner->setSector($data['sector']);
                    $practitioner->setWebsite($data['website']);
                    $practitioner->setPhoneNumber($data['phoneNumber']);
                    $practitioner->setCity($data['city']);
                    $practitioner->setPostalCode($data['postalCode']);
                    $practitioner->setIsVitalCardAccepted($data['isVitalCardAccepted']);

                    $practitioner->setEmail($data["email"]);
                    $practitioner->setExperience((int)$data["experience"]);
                    $practitioner->setDateOfBirth($data["dateOfBirth"]);
                    $practitioner->setPlaceOfBirth($data["placeOfBirth"]);
                    $practitioner->setWaitingTime($data["waitingTime"]);
                    $practitioner->setIsEmergencyAccepted($data["isEmergencyAccepted"]);
                    $practitioner->setPublic($data["public"]);
                    $practitioner->setPaymentMethods($data["paymentMethods"]);

                    $practitioner->setConsultationTypes($data["consultationTypes"]);

                    foreach ($data["specialities"] as $speciality) {
                        if (null != $speciality) {
                            $specialityEntity = $this->manager->getRepository(Speciality::class)->findOneByName($speciality);
                            if (null === $specialityEntity) {
                                throw new \Exception("La spécialité ". $speciality. " n'existe pas");
                            }
                            $practitioner->addSpeciality($specialityEntity);
                        }
                    }
                    foreach ($data["equipments"] as $equipment) {
                        if (null != $equipment) {
                            $equipmentEntity = $this->manager->getRepository(Equipment::class)->findOneByName($equipment);
                            if (null === $equipmentEntity) {
                                throw new \Exception("L'equipement $equipment n'existe pas");
                            }
                            $practitioner->addEquipment($equipmentEntity);
                        }
                    }
                    foreach ($data["accessibilityCriterions"] as $accessibilityCriterion) {
                        if (null != $accessibilityCriterion) {
                            $accessibilityCriterionEntity = $this->manager->getRepository(AccessibilityCriterion::class)->findOneByName($accessibilityCriterion);
                            if (null === $accessibilityCriterionEntity) {
                                throw new \Exception("Le critère d'accessibilité $accessibilityCriterion n'existe pas");
                            }
                            $practitioner->addAccessibilityCriterion($accessibilityCriterionEntity);
                        }
                    }
                    foreach ($data["temperaments"] as $temperament) {
                        if (null != $temperament) {
                            $temperamentEntity = $this->manager->getRepository(Temperament::class)->findOneByName($temperament);
                            if (null === $temperamentEntity) {
                                throw new \Exception("La temperament $temperament n'existe pas");
                            }
                            $practitioner->addTemperament($temperamentEntity);
                        }
                    }
                    foreach ($data["additionalCriterions"] as $additionalCriterion) {
                        if (null != $additionalCriterion) {
                            $additionalCriterionEntity = $this->manager->getRepository(AdditionalCriterion::class)->findOneByName($additionalCriterion);
                            if (null === $additionalCriterionEntity) {
                                throw new \Exception("Le critère additionnel $additionalCriterion n'existe pas");
                            }
                            $practitioner->addAdditionalCriterion($additionalCriterionEntity);
                        }
                    }
                    foreach ($data["pathologies"] as $pathology) {
                        if (null != $pathology) {
                            $pathologyEntity = $this->manager->getRepository(Pathology::class)->findOneByName($pathology);
                            if (null === $pathologyEntity) {
                                throw new \Exception("La pathologie $pathology n'existe pas");
                            }
                            $practitioner->addPathology($pathologyEntity);
                        }
                    }
                    foreach ($data["languages"] as $language) {
                        if (null != $language) {
                            $languageEntity = $this->manager->getRepository(Language::class)->findOneByName($language);
                            if (null === $languageEntity) {
                                throw new \Exception("La langue $language n'existe pas");
                            }
                            $practitioner->addLanguage($languageEntity);
                        }
                    }

                    $this->manager->persist($practitioner);
                }
            $this->manager->flush();
        } catch (PDOException $e) {
            $response["error"] = 'Could not connect : ' . $e->getMessage();
        } catch (\Exception $e) {
            $response["error"] = $e->getMessage();
        }
        if (empty($response["error"])) {
            $response["success"] = "Success";
       }

        return $response;
    }

    /**
     * @param string $string
     * @return false|string|null
     */
    public function decode($string = ""){
        if ("" !== $string) {
            if (!mb_detect_encoding($string, 'UTF-8', true)) {
                return utf8_encode(html_entity_decode(trim($string)));
            }
            return trim($string);
        }

        return null;
    }

    /**
     * @param string|null $string
     * @return false|string[]
     */
    public function stringToArray(?string $string){
        $result = explode("/", $string);
        return $result;
    }
}