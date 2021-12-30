<?php

namespace App\Entity;

use App\Repository\AccessibilityCriterionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AccessibilityCriterionRepository::class)
 */
class AccessibilityCriterion
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
     * @ORM\ManyToMany(targetEntity=Practitioner::class, mappedBy="accessibilityCriterions")
     */
    private $practitioners;

    /**
     * @ORM\ManyToOne(targetEntity=AccessibilityCriterion::class, inversedBy="accessibilityCriterions")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=AccessibilityCriterion::class, mappedBy="parent")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $subAccessibilityCriterions;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    public function __construct()
    {
        $this->practitioners = new ArrayCollection();
        $this->subAccessibilityCriterions = new ArrayCollection();
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
            $practitioner->addAccessibilityCriterion($this);
        }

        return $this;
    }

    public function removePractitioner(Practitioner $practitioner): self
    {
        if ($this->practitioners->removeElement($practitioner)) {
            $practitioner->removeAccessibilityCriterion($this);
        }

        return $this;
    }
    public function __toString(){
        return $this->name;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSubAccessibilityCriterions(): Collection
    {
        return $this->subAccessibilityCriterions;
    }

    public function addSubAccessibilityCriterion(self $accessibilityCriterion): self
    {
        if (!$this->subAccessibilityCriterions->contains($accessibilityCriterion)) {
            $this->subAccessibilityCriterions[] = $accessibilityCriterion;
            $accessibilityCriterion->setParent($this);
        }

        return $this;
    }

    public function removeSubAccessibilityCriterion(self $accessibilityCriterion): self
    {
        if ($this->subAccessibilityCriterions->removeElement($accessibilityCriterion)) {
            // set the owning side to null (unless already changed)
            if ($accessibilityCriterion->getParent() === $this) {
                $accessibilityCriterion->setParent(null);
            }
        }

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }
}
