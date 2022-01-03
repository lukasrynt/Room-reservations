<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 * @ExclusionPolicy("all")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Expose
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Expose
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="managedGroups")
     */
    private ?User $groupManager = null;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="group")
     * @Expose
     */
    private Collection $members;

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="group")
     * @Expose
     */
    private Collection $rooms;

    /**
     * One Group has Many SubGroups.
     * @ORM\OneToMany(targetEntity="Group", mappedBy="parent")
     */
    private Collection $children;


    /**
     * Many subgroups have One group.
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="children")
     */
    private ?Group $parent = null;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->rooms = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function __sleep()
    {
        return [];
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

    public function getGroupManager(): ?User
    {
        return $this->groupManager;
    }

    public function setGroupManager(?User $groupManager): self
    {
        $this->groupManager = $groupManager;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setGroup($this);
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getGroup() === $this) {
                $member->setGroup(null);
            }
        }

        return $this;
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
            $room->setGroup($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->removeElement($room)) {
            // set the owning side to null (unless already changed)
            if ($room->getGroup() === $this) {
                $room->setGroup(null);
            }
        }

        return $this;
    }


    public function getChildren(): Collection
    {
        return $this->children;
    }


    public function addChild(Group $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
        }

        return $this;
    }


    public function getParent(): ?Group
    {
        return $this->parent;
    }


    public function setParent(Group $parent): self
    {
        $this->parent = $parent;

        return $this;
    }


}
