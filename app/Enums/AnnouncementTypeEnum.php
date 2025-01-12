<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AnnouncementTypeEnum: int implements HasLabel, HasColor
{
    case PROVIDE = 1;
    case REQUEST = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PROVIDE => 'Предлагаю',
            self::REQUEST => 'Ищу',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::PROVIDE => 'success',
            self::REQUEST => 'warning'
        };
    }
}
