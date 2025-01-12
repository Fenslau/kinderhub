<?php

namespace App\Filament\Resources;

use App\Enums\AnnouncementTypeEnum;
use App\Filament\Resources\AnnouncementResource\Pages;
use App\Filament\Resources\AnnouncementResource\RelationManagers;
use App\Models\Announcement;
use App\Models\CareCategory;
use App\Models\Scopes\ActiveScope;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Объявление';
    protected static ?string $pluralModelLabel = 'Объявления';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Автор')
                    ->disabled(fn(?Model $record): bool => !request()->user()->isAdmin())
                    ->relationship('user', 'name')
                    ->required()
                    ->default(request()->user()->id)
                    ->selectablePlaceholder(false),
                Radio::make('type')
                    ->label('Тип объявления')
                    ->options(AnnouncementTypeEnum::class)
                    ->default(AnnouncementTypeEnum::PROVIDE),
                Radio::make('care_category_id')
                    ->label('Категория услуги')
                    ->required()
                    ->options(CareCategory::orderBy('sort')->pluck('title', 'id'))
                    ->descriptions(CareCategory::orderBy('sort')->pluck('description', 'id'))
                    ->inline()
                    ->inlineLabel(false)
                    ->live(),
                Select::make('sub_category')
                    ->label('Подкатегория услуги')
                    ->multiple()
                    ->options(fn(Get $get): Collection => collect(array_column(CareCategory::query()
                        ->where('id', $get('care_category_id'))
                        ->first()?->sub_category ?? [], 'title', 'title'))),
                TextInput::make('title')
                    ->label('Заголовок')
                    ->required()
                    ->maxLength(255)
                    ->live(debounce: 500)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', SlugService::createSlug(Announcement::class, 'slug', $state ?? ''))),
                TextInput::make('slug')
                    ->required()
                    ->label('Ссылка')
                    ->prefix(env('APP_URL') . '/announcements/')
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->disabled()
                    ->maxLength(255),
                RichEditor::make('content')
                    ->label('Текст объявления')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                    ])
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Активно')
                    ->default(true)
                    ->disabled(fn(?Model $record): bool => !request()->user()->isAdmin()),
                Toggle::make('is_global')
                    ->label('На главной')
                    ->disabled(fn(?Model $record): bool => !request()->user()->isAdmin())
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Автор')
                    ->sortable()
                    ->limit(16)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (Str::length($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    })
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Тип')
                    ->sortable()
                    ->badge()
                    ->description(fn(Announcement $record): string => $record->careCategory->title, position: 'above'),
                TextColumn::make('sub_category')
                    ->label('Подкатегория')
                    ->badge()
                    ->color('info'),
                TextColumn::make('title')
                    ->label('Заголовок')
                    ->sortable()
                    ->limit(32)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (Str::length($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    })
                    ->searchable(),
                ToggleColumn::make('is_active')
                    ->label('Активно')
                    ->disabled(fn(Model $record): bool => !request()->user()->isAdmin())
                    ->sortable(),
                ToggleColumn::make('is_global')
                    ->label('На главной')
                    ->disabled(fn(Model $record): bool => !request()->user()->isAdmin())
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label('Удалено')
                    ->date()
                    ->timeTooltip()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->date()
                    ->timeTooltip()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('updated_at')
                    ->label('Изменено')
                    ->date()
                    ->timeTooltip()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->recordClasses(fn(Model $record) => match ($record->isActive() && !$record->trashed()) {
                false => 'bg-gray-100',
                default => null,
            })
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
                ActiveScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
