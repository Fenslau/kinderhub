<?php

namespace App\Filament\Resources;

use App\Enums\UserRoleEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                    ->maxLength(255)
                    ->disabled(!request()->user()->isAdmin()),
                DateTimePicker::make('email_verified_at')
                    ->label('Email подтвержден')
                    ->default(now())
                    ->maxDate(today()->addDay())
                    ->disabled(!request()->user()->isAdmin()),
                Group::make()
                    ->relationship('profile')
                    ->schema([
                        Select::make('role')
                            ->label('Роль')
                            ->options(UserRoleEnum::getSelects())
                            ->default(UserRoleEnum::USER->value)
                            ->selectablePlaceholder(false)
                            ->disabled(!request()->user()->isAdmin()),

                        FileUpload::make('image')
                            ->label('Аватар')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                            ->directory('user-images')
                            ->maxSize(5000),
                        Toggle::make('active')
                            ->label('Активен')
                            ->disabled(!request()->user()->isAdmin()),
                    ]),
                TextInput::make('password')
                    ->label('Пароль')
                    ->password()
                    ->revealable()
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Имя')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->label('Email подтвержден')
                    ->dateTime()
                    ->sortable(),
                SelectColumn::make('profile.role')
                    ->label('Роль')
                    ->options(UserRoleEnum::getSelects())
                    ->selectablePlaceholder(false)
                    ->disabled(!request()->user()->isAdmin())
                    ->sortable(),
                ImageColumn::make('profile.image')
                    ->label('Аватар')
                    ->circular(),
                ToggleColumn::make('profile.active')
                    ->label('Активен')
                    ->disabled(!request()->user()->isAdmin()),
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
}
