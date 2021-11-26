<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminRepository;


/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 * @ORM\Table(name="admin")
 */
class Admin extends User
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $admin_id;



    /**
     * @return int
     */
    public function getAdminId(): int
    {
        return $this->admin_id;
    }

}