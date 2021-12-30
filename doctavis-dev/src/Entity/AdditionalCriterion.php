<?php

namespace App\Entity;

use App\Repository\AdditionalCriterionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AdditionalCriterionRepository::class)
 */
class AdditionalCriterion
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
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Practitioner::class, mappedBy="additionalCriterions")
     */
    private $practitioners;

    public function __construct()
    {
        $this->practitioners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Practitioner[]
     */
    public function getPractitioners(): Collection
    {
        return $this->practitioners;
    }

    public function addPractitioner(Practitioner $practitioner): self
    {
        if (!$this->practitioners->contains($practitioner)) {
            $this->practitioners[] = $practitioner;
            $practitioner->addAddtionalCriterion($this);
        }

        return $this;
    }

    public function removePractitioner(Practitioner $practitioner): self
    {
        if ($this->practitioners->removeElement($practitioner)) {
            $practitioner->removeAddtionalCriterion($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
