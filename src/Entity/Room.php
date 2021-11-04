<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

class MyDateTime extends \DateTime
{
    public function __toString()
    {
        return $this->format("H:i");
    }
}

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $capacity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="integer")
     */
    private int $floor;

    /**
     * @ORM\Column(type="time")
     */
    private \DateTime $opened_from;

    /**
     * @ORM\Column(type="time")
     */
    private \DateTime $opened_to;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
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

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getOpened_from():string
    {
        return $this->opened_from->format("H:i");
    }

    public function getOpenedFrom(): \DateTime
    {
        return $this->opened_from;
    }

    public function setOpenedFrom(\DateTime $opened_from): self
    {
        $this->opened_from = $opened_from;
        return $this;
    }

    public function getOpened_to(): string
    {
        return $this->opened_to->format("H:i");
    }

    public function getOpenedTo(): \DateTime
    {
        return $this->opened_to;
    }

    public function setOpenedTo(\DateTime $opened_to): self
    {
        $this->opened_to = $opened_to;

        return $this;
    }
}
