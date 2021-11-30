<?php

namespace App\Entity;

use MyCLabs\Enum\Enum;

class Roles extends Enum
{
    const COMMON_USER = 'COMMON_USER';
    const ROOM_ADMIN = 'ROOM_ADMIN';
    const GROUP_ADMIN = 'GROUP_ADMIN';
    const ADMIN = 'ADMIN';
}