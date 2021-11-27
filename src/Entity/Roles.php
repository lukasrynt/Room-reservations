<?php

namespace App\Entity;

use MyCLabs\Enum\Enum;

class Roles extends Enum
{
    const ROOM_MEMBER = 'ROOM_MEMBER';
    const ROOM_ADMIN = 'ROOM_ADMIN';
    const GROUP_MEMBER = 'GROUP_MEMBER';
    const GROUP_ADMIN = 'GROUP_ADMIN';
    const ADMIN = 'ADMIN';
}