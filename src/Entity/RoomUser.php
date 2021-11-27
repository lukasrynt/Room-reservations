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
    private Collection $member_rooms;


    public function __construct()
    {
        parent::__construct();
        $this->member_rooms = new ArrayCollection();
    }


    /**
     * @return Collection|Group[]
     */
    public function getRooms(): Collection
    {
        return $this->member_rooms;
    }

    public function addRoom(Room $member_room): self
    {
        if (!$this->member_rooms->contains($member_room)) {
            $this->member_rooms[] = $member_room;
            $member_room->addRegisteredUser($this);
        }

        return $this;
    }

    public function removeRoom(Room $member_room): self
    {
        if ($this->member_rooms->removeElement($member_room)) {
            $member_room->removeRegisteredUser($this);
        }

        return $this;
    }


}