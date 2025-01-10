<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CommentableEnum: string implements HasLabel
{
    case COMMENT = 'App\Models\Comment';
    case ARTICLE = 'App\Models\Article';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::COMMENT => 'Комментарий',
            self::ARTICLE => 'Статья',
        };
    }
}
