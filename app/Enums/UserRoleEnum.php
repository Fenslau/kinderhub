<?php

namespace App\Enums;

use App\Enums\Traits\EnumTrait;

enum UserRoleEnum: int
{
    use EnumTrait;

    case USER = 1;
    case MODERATOR = 5;
    case ADMIN = 10;

    private static function getDescription(self $case): string
    {
        return match ($case) {
            self::USER => 'Пользователь',
            self::MODERATOR => 'Модератор',
            self::ADMIN => 'Администратор'
        };
    }
}
