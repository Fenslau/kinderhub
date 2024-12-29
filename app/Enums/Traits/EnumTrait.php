<?php

namespace App\Enums\Traits;

trait EnumTrait
{
    public static function getName($key): string
    {
        foreach (self::cases() as $case) {
            if ($case->value === $key) {
                return self::getDescription($case);
            }
        }
        return '';
    }

    public static function getNames(): array
    {
        $names = [];
        foreach (self::cases() as $case) {
            $names[$case->value] = self::getDescription($case);
        }
        return $names;
    }

    public static function getSelects(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = self::getDescription($case);
        }
        return $options;
    }
}
