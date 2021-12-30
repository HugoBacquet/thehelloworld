<?php

namespace App\Entity;

use App\Repository\DistanceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DistanceRepository::class)
 */
class Distance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $origin;


    /**
     * @ORM\Column(type="integer")
     */
    private $destination;


    public function getDestination(): ?int
    {
        return $this->destination;
    }


    public function setDestination(int $destination): self
    {
        $this->destination = $destination;
        return $this;
    }

    public function getOrigin(): ?int
    {
        return $this->origin;
    }


    public function setOrigin(int $origin): self
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @ORM\Column(type="integer")
     */
    private $distance;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): self
    {
        $this->distance = $distance;

        return $this;
    }
}
