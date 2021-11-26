<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Entity\Roles;

class EnumRolesType extends Type
{

    // return the SQL used to create your column type. To create a portable column type, use the $platform.
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return "VARCHAR(20)";
    }

    // This is executed when the value is read from the database. Make your conversions here, optionally using the $platform.
    public function convertToPHPValue($value, AbstractPlatform $platform): Roles
    {
        if (! Roles::isValid($value)) {
            throw new \InvalidArgumentException(sprintf(
                'The value "%s" is not valid for the enum "%s".',
                $value,
                Roles::class,
            ));
        }
        return new Roles($value);
    }

    // This is executed when the value is written to the database. Make your conversions here, optionally using the $platform.
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!in_array($value, array("ROOM_MEMBER", "ROOM_ADMIN", "GROUP_MEMBER", "GROUP_ADMIN", "ADMIN"))) {
            throw new \InvalidArgumentException("Invalid role");
        }
        return (string) $value;
    }

    // modify to match your constant name
    public function getName(): string
    {
        return "enum_roles_type";
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}