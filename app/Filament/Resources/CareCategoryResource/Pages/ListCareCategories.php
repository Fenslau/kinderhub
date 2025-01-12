<?php

namespace App\Filament\Resources\CareCategoryResource\Pages;

use App\Filament\Resources\CareCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCareCategories extends ListRecords
{
    protected static string $resource = CareCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
