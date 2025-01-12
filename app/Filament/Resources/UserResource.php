<?php

namespace App\Filament\Resources;

use App\Enums\UserRoleEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\City;
use App\Models\Scopes\ActiveScope;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Пользователь';
    protected static ?string $pluralModelLabel = 'Пользователи';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Имя')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->suffixIcon('heroicon-m-envelope')
                    ->maxLength(255)
                    ->disabled(!request()->user()->isAdmin()),
                DateTimePicker::make('email_verified_at')
                    ->label('Email подтвержден')
                    ->default(now())
                    ->disabled(!request()->user()->isAdmin()),
                TextInput::make('password')
                    ->label('Пароль')
                    ->password()
                    ->revealable()
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->maxLength(255),
                Fieldset::make()
                    ->relationship('profile')
                    ->schema([
                        Select::make('role')
                            ->label('Роль')
                            ->options(UserRoleEnum::class)
                            ->default(UserRoleEnum::USER)
                            ->selectablePlaceholder(false)
                            ->disabled(fn(?Model $record): bool => !request()->user()->isAdmin() || $record?->id === request()->user()->id),
                        TextInput::make('phone')
                            ->label('Телефон')
                            ->mask('+7 999 999 99 99')
                            ->suffixIcon('heroicon-m-phone'),
                        Select::make('region_id')
                            ->label('Регион')
                            ->options(City::where('owner', 481)->orderBy('order')->pluck('name', 'id'))
                            ->default(1070)
                            ->searchable()
                            ->live(),
                        Select::make('city_id')
                            ->label('Населенный пункт')
                            ->options(fn(Get $get): Collection => City::query()
                                ->where('owner', $get('region_id'))
                                ->orderBy('order')
                                ->pluck('name', 'id'))
                            ->default(26405)
                            ->searchable(),
                        FileUpload::make('image')
                            ->label('Аватар')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                            ->directory('user-images')
                            ->dehydrated(fn(?array $state, ?Model $record): bool => !(Str::startsWith($record?->image, 'http') && !count($state)))
                            ->maxSize(5000),
                        RichEditor::make('about')
                            ->label('Информация')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                            ]),
                        Toggle::make('is_active')
                            ->label('Активен')
                            ->default(true)
                            ->disabled(fn(?Model $record): bool => !request()->user()->isAdmin() || $record?->id === request()->user()->id)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Имя')
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
                TextColumn::make('email')
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
                IconColumn::make('email_verified_at')
                    ->label('Подтв.')
                    ->boolean()
                    ->alignCenter()
                    ->sortable(),
                SelectColumn::make('profile.role')
                    ->label('Роль')
                    ->options(UserRoleEnum::class)
                    ->selectablePlaceholder(false)
                    ->disabled(fn(Model $record): bool => !request()->user()->isAdmin() || $record->id === request()->user()->id)
                    ->sortable(),
                ImageColumn::make('profile.image')
                    ->label('Аватар')
                    ->circular(),
                ToggleColumn::make('profile.is_active')
                    ->label('Активен')
                    ->disabled(fn(Model $record): bool => !request()->user()->isAdmin() || $record->id === request()->user()->id)
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
                Tables\Filters\TrashedFilter::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
