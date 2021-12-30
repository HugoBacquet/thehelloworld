<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 */
class Patient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"export-patient"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"export-patient"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"export-patient"})
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"export-patient"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"export-patient"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"export-patient"})
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $doctavisNews;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $associatesNews;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

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
