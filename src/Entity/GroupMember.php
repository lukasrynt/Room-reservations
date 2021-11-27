<?php

namespace App\Entity;

use App\Repository\GroupMemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupMemberRepository::class)
 */
class GroupMember extends User
{

    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
    }

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, inversedBy="members")
     * @ORM\JoinTable(name="members_groups")
     */
    private Collection $groups;

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }


    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        $this->groups->removeElement($group);

        return $this;
    }
}
