<?php

namespace App\Entity;

use App\Repository\ImportanceCriterionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImportanceCriterionRepository::class)
 */
class ImportanceCriterion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=PractitionerImportanceCriterion::class, mappedBy="criterion")
     */
    private $practitionerImportanceCriteria;

    public function __construct()
    {
        $this->practitionerImportanceCriteria = new ArrayCollection();
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
     * @return Collection|PractitionerImportanceCriterion[]
     */
    public function getPractitionerImportanceCriteria(): Collection
    {
        return $this->practitionerImportanceCriteria;
    }

    public function addPractitionerImportanceCriterion(PractitionerImportanceCriterion $practitionerImportanceCriterion): self
    {
        if (!$this->practitionerImportanceCriteria->contains($practitionerImportanceCriterion)) {
            $this->practitionerImportanceCriteria[] = $practitionerImportanceCriterion;
            $practitionerImportanceCriterion->setCriterion($this);
        }

        return $this;
    }

    public function removePractitionerImportanceCriterion(PractitionerImportanceCriterion $practitionerImportanceCriterion): self
    {
        if ($this->practitionerImportanceCriteria->removeElement($practitionerImportanceCriterion)) {
            // set the owning side to null (unless already changed)
            if ($practitionerImportanceCriterion->getCriterion() === $this) {
                $practitionerImportanceCriterion->setCriterion(null);
            }
        }

        return $this;
    }
//    public function __toString() {
//        return $this->name;
//    }
}
