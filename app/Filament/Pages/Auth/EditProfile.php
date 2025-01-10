<?php

namespace App\Filament\Pages\Auth;

use App\Enums\UserRoleEnum;
use App\Models\City;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
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
                            ->default(1)
                            ->disabled(fn(?Model $record): bool => !request()->user()->isAdmin() || $record?->id === request()->user()->id)
                    ])
                    ->columns(1),
            ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->disabled();
    }
}
