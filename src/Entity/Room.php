<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;

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
    private \DateTimeInterface $opened_from;

    /**
     * @ORM\Column(type="time")
     */
    private \DateTimeInterface $opened_to;

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

    public function getOpenedFrom(): ?\DateTimeInterface
    {
        return $this->opened_from;
    }

    public function setOpenedFrom(\DateTimeInterface $opened_from): self
    {
        $this->opened_from = $opened_from;

        return $this;
    }

    public function getOpenedTo(): ?\DateTimeInterface
    {
        return $this->opened_to;
    }

    public function setOpenedTo(\DateTimeInterface $opened_to): self
    {
        $this->opened_to = $opened_to;

        return $this;
    }
}
