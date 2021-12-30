<?php

namespace App\Entity;

use App\Repository\PathologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PathologyRepository::class)
 */
class Pathology
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
     * @ORM\ManyToOne(targetEntity=Pathology::class, inversedBy="subPathologies")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Pathology::class, mappedBy="parent")
     */
    private $subPathologies;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\ManyToMany(targetEntity=Practitioner::class, mappedBy="pathologies")
     */
    private $practitioners;

    public function __construct()
    {
        $this->subPathologies = new ArrayCollection();
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
     * @return Collection|self[]
     */
    public function getSubPathologies(): Collection
    {
        return $this->subPathologies;
    }

    public function addSubPathology(self $subPathology): self
    {
        if (!$this->subPathologies->contains($subPathology)) {
            $this->subPathologies[] = $subPathology;
            $subPathology->setParent($this);
        }

        return $this;
    }

    public function removeSubPathology(self $subPathology): self
    {
        if ($this->subPathologies->removeElement($subPathology)) {
            // set the owning side to null (unless already changed)
            if ($subPathology->getParent() === $this) {
                $subPathology->setParent(null);
            }
        }

        return $this;
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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
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
            $practitioner->addPathology($this);
        }

        return $this;
    }

    public function removePractitioner(Practitioner $practitioner): self
    {
        if ($this->practitioners->removeElement($practitioner)) {
            $practitioner->removePathology($this);
        }

        return $this;
    }
}
