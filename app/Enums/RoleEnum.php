<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case USER = 'user';

    public static function getAllRoles(): array
    {
        return array_column(self::cases(), 'value');
    }
}
