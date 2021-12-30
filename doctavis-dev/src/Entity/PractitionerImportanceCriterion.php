<?php

namespace App\Entity;

use App\Repository\PractitionerImportanceCriterionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PractitionerImportanceCriterionRepository::class)
 */
class PractitionerImportanceCriterion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=ImportanceCriterion::class, inversedBy="practitionerImportanceCriteria")
     */
    private $criterion;

    /**
     * @ORM\ManyToOne(targetEntity=Practitioner::class, inversedBy="practitionerImportanceCriterions")
     * @ORM\JoinColumn(name="practitioner_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $practitioner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getCriterion(): ?ImportanceCriterion
    {
        return $this->criterion;
    }

    public function setCriterion(?ImportanceCriterion $criterion): self
    {
        $this->criterion = $criterion;

        return $this;
    }

    public function getPractitioner(): ?Practitioner
    {
        return $this->practitioner;
    }

    public function setPractitioner(?Practitioner $practitioner): self
    {
        $this->practitioner = $practitioner;

        return $this;
    }
//    public function __toString() {
//        return $this->getCriterion()->getName();
//    }

}
