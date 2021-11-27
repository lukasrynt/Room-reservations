<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoomUserRepository::class)
 */
class RoomUser extends User
{
    /**
     * @ORM\ManyToMany(targetEntity=Room::class, mappedBy="registeredUsers")
     */
    private Collection $rooms;


    public function __construct()
    {
        parent::__construct();
        $this->rooms = new ArrayCollection();
    }


    /**
     * @return Collection
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->addRegisteredUser($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->removeElement($room)) {
            $room->removeRegisteredUser($this);
        }

        return $this;
    }


}