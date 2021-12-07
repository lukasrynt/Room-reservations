<?php


namespace App\Entity;

use MyCLabs\Enum\Enum;

class States extends Enum
{
    const PENDING = 'PENDING';
    const APPROVED = 'APPROVED';
    const REJECTED = 'REJECTED';
}