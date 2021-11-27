<?php

namespace App\Entity;

use App\Repository\GroupManagerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupManagerRepository::class)
 */
class GroupManager extends User
{
    /**
     * @ORM\OneToMany(targetEntity=Group::class, mappedBy="groupManager")
     */
    private Collection $groups;

    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
    }

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
            $group->setGroupManager($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getGroupManager() === $this) {
                $group->setGroupManager(null);
            }
        }

        return $this;
    }
}
