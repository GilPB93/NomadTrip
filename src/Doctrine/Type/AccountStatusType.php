<?php

namespace App\Doctrine\Type;

use App\Enum\AccountStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class AccountStatusType extends Type
{
    public const NAME = 'account_status';

    /**
     * Définit comment la colonne doit être créée dans la base de données.
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * Convertit une valeur de la base de données en instance de AccountStatus.
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?AccountStatus
    {
        return $value !== null ? AccountStatus::from($value) : null;
    }

    /**
     * Convertit une instance de AccountStatus en une chaîne pour la base de données.
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof AccountStatus ? $value->value : $value;
    }

    /**
     * Renvoie le nom unique de ce type.
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
