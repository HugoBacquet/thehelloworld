<?php

namespace App\Entity;

use App\Repository\PractitionerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PractitionerRepository::class)
 */
class Practitioner
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"export-practitioner"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"export-practitioner"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"export-practitioner"})
     */
    private $sex;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $experience;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $sector;

    /**
     * @ORM\Column(type="array", length=255, nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $consultationTypes = [];

    /**
     * @ORM\ManyToMany(targetEntity=Speciality::class, inversedBy="practitioners")
     * @Groups({"export-practitioner"})
     */
    private $specialities;

    /**
     * @ORM\ManyToMany(targetEntity=Equipment::class, inversedBy="practitioners")
     * @Groups({"export-practitioner"})
     */
    private $equipments;

    /**
     * @ORM\ManyToMany(targetEntity=AccessibilityCriterion::class, inversedBy="practitioners")
     * @Groups({"export-practitioner"})
     */
    private $accessibilityCriterions;

    /**
     * @ORM\ManyToMany(targetEntity=Temperament::class, inversedBy="practitioners")
     * @Groups({"export-practitioner"})
     */
    private $temperaments;

    /**
     * @ORM\ManyToMany(targetEntity=AdditionalCriterion::class, inversedBy="practitioners")
     * @ORM\JoinColumn(name="additional_criterions", referencedColumnName="id", onDelete="SET NULL")
     * @Groups({"export-practitioner"})
     */
    private $additionalCriterions;

    /**
     * @ORM\OneToMany(targetEntity=PractitionerImportanceCriterion::class, mappedBy="practitioner", cascade={"persist"})
     * @ORM\JoinColumn(name="practitioner_importance_criterions", referencedColumnName="id", onDelete="SET NULL")
     */
    private $practitionerImportanceCriterions;


    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $placeOfBirth;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $waitingTime;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $isEmergencyAccepted;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $public = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $paymentMethods = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $immatriculation;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $isVitalCardAccepted;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $city;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\ManyToMany(targetEntity=Language::class, inversedBy="practitioners")
     * @Groups({"export-practitioner"})
     */
    private $languages;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"export-practitioner"})
     */
    private $isCMUAccepted;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"export-practitioner"})
     */
    private $thirdPartyPayment;

    /**
     * @ORM\ManyToMany(targetEntity=Pathology::class, inversedBy="practitioners")
     * @Groups({"export-practitioner"})
     */
    private $pathologies;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"export-practitioner"})
     */
    private $website;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $doctavisNews;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $associatesNews;

    public function __construct()
    {
        $this->specialities = new ArrayCollection();
        $this->equipments = new ArrayCollection();
        $this->accessibilityCriterions = new ArrayCollection();
        $this->temperaments = new ArrayCollection();
        $this->additionalCriterions = new ArrayCollection();
        $this->practitionerImportanceCriterions = new ArrayCollection();
        $this->isEnabled = true;
        $this->thirdPartyPayment = false;
        $this->isCMUAccepted = false;
        $this->languages = new ArrayCollection();
        $this->pathologies = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }


    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(?int $experience): self
    {
        if ($experience !== 0) {
            $this->experience = $experience;
        }

        return $this;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(?string $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function getConsultationTypes(): ?array
    {
        return $this->consultationTypes;
    }

    public function setConsultationTypes(array $consultationTypes): self
    {
        $this->consultationTypes = $consultationTypes;

        return $this;
    }

    /**
     * @return Collection|speciality[]
     */
    public function getSpecialities(): Collection
    {
        return $this->specialities;
    }

    public function addSpeciality(Speciality $speciality): self
    {
        if (!$this->specialities->contains($speciality)) {
            $this->specialities[] = $speciality;
        }

        return $this;
    }

    public function removeSpeciality(Speciality $speciality): self
    {
        $this->specialities->removeElement($speciality);

        return $this;
    }

    /**
     * @return Collection|Equipment[]
     */
    public function getEquipments(): Collection
    {
        return $this->equipments;
    }

    public function addEquipment(Equipment $equipment): self
    {
        if (!$this->equipments->contains($equipment)) {
            $this->equipments[] = $equipment;
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): self
    {
        $this->equipments->removeElement($equipment);

        return $this;
    }

    /**
     * @return Collection|accessibilityCriterion[]
     */
    public function getAccessibilityCriterions(): Collection
    {
        return $this->accessibilityCriterions;
    }

    public function addAccessibilityCriterion(accessibilityCriterion $accessibilityCriterion): self
    {
        if (!$this->accessibilityCriterions->contains($accessibilityCriterion)) {
            $this->accessibilityCriterions[] = $accessibilityCriterion;
        }

        return $this;
    }

    public function removeAccessibilityCriterion(accessibilityCriterion $accessibilityCriterion): self
    {
        $this->accessibilityCriterions->removeElement($accessibilityCriterion);

        return $this;
    }

    /**
     * @return Collection|Temperament[]
     */
    public function getTemperaments(): Collection
    {
        return $this->temperaments;
    }

    public function addTemperament(Temperament $temperament): self
    {
        if (!$this->temperaments->contains($temperament)) {
            $this->temperaments[] = $temperament;
        }

        return $this;
    }

    public function removeTemperament(Temperament $temperament): self
    {
        $this->temperaments->removeElement($temperament);

        return $this;
    }

    /**
     * @return Collection|AdditionalCriterion[]
     */
    public function getAdditionalCriterions(): Collection
    {
        return $this->additionalCriterions;
    }

    public function addAdditionalCriterion(AdditionalCriterion $additionalCriterion): self
    {
        if (!$this->additionalCriterions->contains($additionalCriterion)) {
            $this->additionalCriterions[] = $additionalCriterion;
        }

        return $this;
    }

    public function removeAddtionalCriterion(AdditionalCriterion $addtionalCriterion): self
    {
        $this->addtionalCriterions->removeElement($addtionalCriterion);

        return $this;
    }

    /**
     * @return Collection|PractitionerImportanceCriterion[]
     */
    public function getPractitionerImportanceCriterions(): Collection
    {
        return $this->practitionerImportanceCriterions;
    }

    public function addPractitionerImportanceCriterion(PractitionerImportanceCriterion $practitionerImportanceCriterion): self
    {
        if (!$this->practitionerImportanceCriterions->contains($practitionerImportanceCriterion)) {
            $this->practitionerImportanceCriterions[] = $practitionerImportanceCriterion;
            $practitionerImportanceCriterion->setPractitioner($this);
        }

        return $this;
    }

    public function removePractitionerImportanceCriterion(PractitionerImportanceCriterion $practitionerImportanceCriterion): self
    {
        if ($this->practitionerImportanceCriterions->removeElement($practitionerImportanceCriterion)) {
            // set the owning side to null (unless already changed)
            if ($practitionerImportanceCriterion->getPractitioner() === $this) {
                $practitionerImportanceCriterion->setPractitioner(null);
            }
        }

        return $this;
    }


    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth($dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getPlaceOfBirth(): ?string
    {
        return $this->placeOfBirth;
    }

    public function setPlaceOfBirth(?string $placeOfBirth): self
    {
        $this->placeOfBirth = $placeOfBirth;

        return $this;
    }

    public function getWaitingTime(): ?string
    {
        return $this->waitingTime;
    }

    public function setWaitingTime(?string $waitingTime): self
    {
        $this->waitingTime = $waitingTime;

        return $this;
    }

    public function getIsEmergencyAccepted(): ?bool
    {
        return $this->isEmergencyAccepted;
    }

    public function setIsEmergencyAccepted(?bool $isEmergencyAccepted): self
    {
        $this->isEmergencyAccepted = $isEmergencyAccepted;

        return $this;
    }

    public function getPublic(): ?array
    {
        return $this->public;
    }

    public function setPublic(array $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getPaymentMethods(): ?array
    {
        return $this->paymentMethods;
    }

    public function setPaymentMethods(array $paymentMethods): self
    {
        $this->paymentMethods = $paymentMethods;

        return $this;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(?string $immatriculation): self
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getIsVitalCardAccepted(): ?bool
    {
        return $this->isVitalCardAccepted;
    }

    public function setIsVitalCardAccepted(?bool $isVitalCardAccepted): self
    {
        $this->isVitalCardAccepted = $isVitalCardAccepted;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(?int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(?bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }


    public function removeAdditionalCriterion(AdditionalCriterion $additionalCriterion): self
    {
        $this->additionalCriterions->removeElement($additionalCriterion);

        return $this;
    }

    /**
     * @return Collection|Language[]
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages[] = $language;
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        $this->languages->removeElement($language);

        return $this;
    }

    public function getIsCMUAccepted(): ?bool
    {
        return $this->isCMUAccepted;
    }

    public function setIsCMUAccepted(bool $isCMUAccepted): self
    {
        $this->isCMUAccepted = $isCMUAccepted;

        return $this;
    }

    public function getThirdPartyPayment(): ?bool
    {
        return $this->thirdPartyPayment;
    }

    public function setThirdPartyPayment(bool $thirdPartyPayment): self
    {
        $this->thirdPartyPayment = $thirdPartyPayment;

        return $this;
    }

    /**
     * @return Collection|Pathology[]
     */
    public function getPathologies(): Collection
    {
        return $this->pathologies;
    }

    public function addPathology(Pathology $pathology): self
    {
        if (!$this->pathologies->contains($pathology)) {
            $this->pathologies[] = $pathology;
        }

        return $this;
    }

    public function removePathology(Pathology $pathology): self
    {
        $this->pathologies->removeElement($pathology);

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getDoctavisNews(): ?bool
    {
        return $this->doctavisNews;
    }

    public function setDoctavisNews(?bool $doctavisNews): self
    {
        $this->doctavisNews = $doctavisNews;

        return $this;
    }

    public function getAssociatesNews(): ?bool
    {
        return $this->associatesNews;
    }

    public function setAssociatesNews(?bool $associatesNews): self
    {
        $this->associatesNews = $associatesNews;

        return $this;
    }
}
