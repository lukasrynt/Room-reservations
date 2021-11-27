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
    private Collection $rooms;

    public function __construct()
    {
        parent::__construct();
        $this->rooms = new ArrayCollection();
    }

    /**
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->setRoomManager($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->removeElement($room)) {
            // set the owning side to null (unless already changed)
            if ($room->getRoomManager() === $this) {
                $room->setRoomManager(null);
            }
        }

        return $this;
    }
}
