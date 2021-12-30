<?php

namespace App\Entity;

use App\Repository\SpecialityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SpecialityRepository::class)
 */
class Speciality
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
     * @ORM\ManyToMany(targetEntity=Practitioner::class, mappedBy="specialities")
     */
    private $practitioners;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity=Speciality::class, inversedBy="subSpecialities")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Speciality::class, mappedBy="parent")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $subSpecialities;


    public function __construct()
    {
        $this->practitioners = new ArrayCollection();
        $this->subSpecialities = new ArrayCollection();
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
            $practitioner->addSpeciality($this);
        }

        return $this;
    }

    public function removePractitioner(Practitioner $practitioner): self
    {
        if ($this->practitioners->removeElement($practitioner)) {
            $practitioner->removeSpeciality($this);
        }

        return $this;
    }

    public function __toString(){
        return $this->name;
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
    public function getSubSpecialities(): Collection
    {
        return $this->subSpecialities;
    }

    public function addSubSpeciality(self $subSpeciality): self
    {
        if (!$this->subSpecialities->contains($subSpeciality)) {
            $this->subSpecialities[] = $subSpeciality;
            $subSpeciality->setParent($this);
        }

        return $this;
    }

    public function removeSubSpeciality(self $subSpeciality): self
    {
        if ($this->subSpecialities->removeElement($subSpeciality)) {
            // set the owning side to null (unless already changed)
            if ($subSpeciality->getParent() === $this) {
                $subSpeciality->setParent(null);
            }
        }

        return $this;
    }

}
