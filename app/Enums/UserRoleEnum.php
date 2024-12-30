<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRoleEnum: int implements HasLabel
{
    case USER = 1;
    case MODERATOR = 5;
    case ADMIN = 10;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::USER => 'Пользователь',
            self::MODERATOR => 'Модератор',
            self::ADMIN => 'Администратор',
        };
    }
}
