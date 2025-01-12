<?php

namespace App\Filament\Resources\CareCategoryResource\Pages;

use App\Filament\Resources\CareCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCareCategory extends EditRecord
{
    protected static string $resource = CareCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
