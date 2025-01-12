<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CareCategoryResource\Pages;
use App\Filament\Resources\CareCategoryResource\RelationManagers;
use App\Models\CareCategory;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CareCategoryResource extends Resource
{
    protected static ?string $model = CareCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $modelLabel = 'Категория услуги';
    protected static ?string $pluralModelLabel = 'Категории услуг';
    protected static ?int $navigationSort = 101;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Название')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Краткое описание')
                    ->maxLength(255),
                Repeater::make('sub_category')
                    ->label('Подкатегории')
                    ->schema([
                        TextInput::make('title')
                            ->label('Название')
                            ->required()
                            ->maxLength(255)
                    ])
                    ->reorderableWithButtons()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Название')
                    ->description(fn(CareCategory $record): ?string => Str::limit($record->description, 32))
                    ->searchable(),
                TextColumn::make('sub_category')
                    ->label('Подкатегории')
                    ->formatStateUsing(fn(array $state): string => implode(',', $state))
                    ->badge()
                    ->color('info'),
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Изменено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->paginated(false)
            ->reorderable('sort');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCareCategories::route('/'),
            'create' => Pages\CreateCareCategory::route('/create'),
            'edit' => Pages\EditCareCategory::route('/{record}/edit'),
        ];
    }
}
