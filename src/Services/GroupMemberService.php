<?php


namespace App\Services;


use App\Entity\GroupMember;
use App\Repository\GroupMemberRepository;

class GroupMemberService
{
    private GroupMemberRepository $groupMemberRepository;

    /**
     * GroupMemberService constructor.
     * @param GroupMemberRepository $groupMemberRepository
     */
    public function __construct(GroupMemberRepository $groupMemberRepository)
    {
        $this->groupMemberRepository = $groupMemberRepository;
    }

    /**
     * @param int $id
     * @return GroupMember
     */
    public function find(int $id): GroupMember
    {
        return $this->groupMemberRepository->find($id);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->groupMemberRepository->findAll();
    }
}