<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumRolesType extends Type
{

    const ROLE = 'ROLE';
    const ROOM_MEMBER = 'ROOM_MEMBER';
    const ROOM_ADMIN = 'ROOM_ADMIN';
    const GROUP_MEMBER = 'GROUP_MEMBER';
    const GROUP_ADMIN = 'GROUP_ADMIN';
    const ADMIN = 'ADMIN';

    // return the SQL used to create your column type. To create a portable column type, use the $platform.
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return "VARCHAR(20)";
    }

    // This is executed when the value is read from the database. Make your conversions here, optionally using the $platform.
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    // This is executed when the value is written to the database. Make your conversions here, optionally using the $platform.
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, array(self::ROOM_MEMBER, self::ROOM_ADMIN, self::GROUP_MEMBER,
                                                            self::GROUP_ADMIN, self::ADMIN))) {
            throw new \InvalidArgumentException("Invalid role");
        }
        return $value;
    }

    // modify to match your constant name
    public function getName(): string
    {
        return self::ROLE;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}