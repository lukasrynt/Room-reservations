<?php

namespace App\Entity;

use App\Repository\RoomManagerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoomManagerRepository::class)
 */
class RoomManager extends User
{
    /**
     * @ORM\OneToMany(targetEntity=Room::class, mappedBy="roomManager")
     */
    private Collection $managedRooms;

    public function __construct()
    {
        $this->managedRooms = new ArrayCollection();
        parent::__construct();
    }

    /**
     * @return Collection|Room[]
     */
    public function getManagedRooms(): Collection
    {
        return $this->managedRooms;
    }

    public function addManagedRoom(Room $room): self
    {
        if (!$this->managedRooms->contains($room)) {
            $this->managedRooms[] = $room;
            $room->setRoomManager($this);
        }

        return $this;
    }

    public function removeManagedRoom(Room $room): self
    {
        if ($this->managedRooms->removeElement($room)) {
            // set the owning side to null (unless already changed)
            if ($room->getRoomManager() === $this) {
                $room->setRoomManager(null);
            }
        }

        return $this;
    }
}
