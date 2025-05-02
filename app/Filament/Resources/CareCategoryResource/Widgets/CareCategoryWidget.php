<?php

namespace App\Filament\Resources\CareCategoryResource\Widgets;

use App\Models\CareCategory;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use InvadersXX\FilamentNestedList\Actions\Action;
use InvadersXX\FilamentNestedList\Actions\ActionGroup;
use InvadersXX\FilamentNestedList\Actions\DeleteAction;
use InvadersXX\FilamentNestedList\Actions\EditAction;
use InvadersXX\FilamentNestedList\Actions\ViewAction;
use InvadersXX\FilamentNestedList\Widgets\NestedList as BaseWidget;

class CareCategoryWidget extends BaseWidget
{
    protected static string $model = CareCategory::class;

    protected static int $maxDepth = 10;

    protected ?string $treeTitle = 'Категории услуг';

    protected bool $enableTreeTitle = true;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label('Название')
                ->required()
                ->maxLength(255),
            Textarea::make('description')
                ->label('Краткое описание')
                ->maxLength(255),
        ];
    }

    // INFOLIST, CAN DELETE
    public function getViewFormSchema(): array
    {
        return [];
    }

    // CUSTOMIZE ICON OF EACH RECORD, CAN DELETE
    // public function getTreeRecordIcon(?\Illuminate\Database\Eloquent\Model $record = null): ?string
    // {
    //     return null;
    // }

    // CUSTOMIZE ACTION OF EACH RECORD, CAN DELETE 
    // protected function getTreeActions(): array
    // {
    //     return [
    //         Action::make('helloWorld')
    //             ->action(function () {
    //                 Notification::make()->success()->title('Hello World')->send();
    //             }),
    //         // ViewAction::make(),
    //         // EditAction::make(),
    //         ActionGroup::make([
    //             
    //             ViewAction::make(),
    //             EditAction::make(),
    //         ]),
    //         DeleteAction::make(),
    //     ];
    // }
    // OR OVERRIDE FOLLOWING METHODS
    //protected function hasDeleteAction(): bool
    //{
    //    return true;
    //}
    //protected function hasEditAction(): bool
    //{
    //    return true;
    //}
    //protected function hasViewAction(): bool
    //{
    //    return true;
    //}
}
