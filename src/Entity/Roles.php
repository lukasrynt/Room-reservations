<?php

namespace App\Entity;

use MyCLabs\Enum\Enum;

class Roles extends Enum
{
    const COMMON_USER = 'COMMON_USER';
    const ROOM_ADMIN = 'ROOM_ADMIN';
    const GROUP_ADMIN = 'GROUP_ADMIN';
    const ADMIN = 'ADMIN';

    public static function getAll(): array
    {
        return [
            'Common' => self::COMMON_USER,
            'Room Admin' => self::ROOM_ADMIN,
            'Group Admin' => self::GROUP_ADMIN,
            'Admin' => self::ADMIN
        ];
    }
}