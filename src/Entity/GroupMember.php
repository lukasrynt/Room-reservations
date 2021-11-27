<?php

namespace App\Entity;

use App\Repository\GroupMemberRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupMemberRepository::class)
 */
class GroupMember extends User
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="members")
     */
    private ?Group $memberGroup;

    public function getMemberGroup(): ?Group
    {
        return $this->memberGroup;
    }

    public function setMemberGroup(?Group $memberGroup): self
    {
        $this->memberGroup = $memberGroup;

        return $this;
    }
}
