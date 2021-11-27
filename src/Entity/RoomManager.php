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
    private Collection $managed_rooms;

    public function __construct()
    {
        parent::__construct();
        $this->managed_rooms = new ArrayCollection();
    }

    /**
     * @return Collection|Room[]
     */
    public function getManagedRooms(): Collection
    {
        return $this->managed_rooms;
    }

    public function addManagedRoom(Room $managed_room): self
    {
        if (!$this->managed_rooms->contains($managed_room)) {
            $this->managed_rooms[] = $managed_room;
            $managed_room->setRoomManager($this);
        }

        return $this;
    }

    public function removeManagedRoom(Room $managed_room): self
    {
        if ($this->managed_rooms->removeElement($managed_room)) {
            // set the owning side to null (unless already changed)
            if ($managed_room->getRoomManager() === $this) {
                $managed_room->setRoomManager(null);
            }
        }

        return $this;
    }
}
