<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=GroupManager::class, inversedBy="groups")
     */
    private ?GroupManager $groupManager;

    /**
     * @ORM\OneToMany(targetEntity=GroupMember::class, mappedBy="memberGroup")
     */
    private Collection $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGroupManager(): ?GroupManager
    {
        return $this->groupManager;
    }

    public function setGroupManager(?GroupManager $groupManager): self
    {
        $this->groupManager = $groupManager;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(GroupMember $groupMember): self
    {
        if (!$this->members->contains($groupMember)) {
            $this->members[] = $groupMember;
            $groupMember->setMemberGroup($this);
        }

        return $this;
    }

    public function removeMember(GroupMember $groupMember): self
    {
        if ($this->members->removeElement($groupMember)) {
            // set the owning side to null (unless already changed)
            if ($groupMember->getMemberGroup() === $this) {
                $groupMember->setMemberGroup(null);
            }
        }

        return $this;
    }


}
