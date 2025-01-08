<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Filament\Forms;
use Filament\Forms\Components\Builder as ComponentsBuilder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $modelLabel = 'Статья';
    protected static ?string $pluralModelLabel = 'Статьи';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Автор')
                    ->required()
                    ->selectablePlaceholder(false)
                    ->default(request()->user()->id),
                TextInput::make('slug')
                    ->required()
                    ->label('Ссылка')
                    ->prefix(env('APP_URL') . '/articles/')
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->disabled()
                    ->maxLength(255),
                TextInput::make('title')
                    ->label('Название')
                    ->required()
                    ->maxLength(255)
                    ->live(debounce: 500)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', SlugService::createSlug(Article::class, 'slug', $state ?? ''))),
                DateTimePicker::make('created_at')
                    ->label('Создано')
                    ->default(now())
                    ->seconds(false),
                Textarea::make('description')
                    ->label('Описание (для SEO)')
                    ->rows(3)
                    ->maxLength(1000),
                Fieldset::make()
                    ->columns(1)
                    ->schema([
                        ComponentsBuilder::make('content')
                            ->label('Контент')
                            ->blocks([
                                ComponentsBuilder\Block::make('editor')
                                    ->label('Блок текста')
                                    ->icon('heroicon-m-bars-3-bottom-left')
                                    ->schema([
                                        RichEditor::make('editor')
                                            ->label('Блок текста')
                                            ->fileAttachmentsDirectory('article-images')
                                            ->required(),
                                    ])
                                    ->columns(1),
                                ComponentsBuilder\Block::make('image')
                                    ->label('Изображение')
                                    ->icon('heroicon-m-photo')
                                    ->schema([
                                        FileUpload::make('url')
                                            ->label('Изображение')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('article-images')
                                            ->required(),
                                        TextInput::make('title')
                                            ->label('Название')
                                            ->maxLength(255),
                                        TextInput::make('description')
                                            ->label('Описание')
                                            ->maxLength(255),
                                    ])
                                    ->columns(1),
                            ])
                            ->reorderableWithButtons()
                            ->blockIcons(),
                    ]),
                Toggle::make('is_active')
                    ->label('Активно')
                    ->disabled(fn(?Model $record): bool => !request()->user()->isAdmin())
                    ->default(fn(?Model $record): bool => request()->user()->isAdmin()),
                Toggle::make('is_global')
                    ->label('На главной')
                    ->disabled(fn(?Model $record): bool => !request()->user()->isAdmin())
                    ->default(0)
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
                TextColumn::make('title')
                    ->label('Название')
                    ->description(fn(Article $record): ?string => Str::limit($record->description, 32))
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
                ImageColumn::make('image')
                    ->label('Изображение')
                    ->defaultImageUrl(fn(Article $record): ?string =>
                    collect($record->content)->firstWhere('type', 'image')['data']['url'] ?? null
                        ? Storage::url(collect($record->content)->firstWhere('type', 'image')['data']['url'] ?? null)
                        : null)
                    ->extraImgAttributes(fn(Article $record): array => [
                        'alt' => collect($record->content)->firstWhere('type', 'image')['data']['title'] ?? null,
                    ]),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->recordClasses(fn(Model $record) => match ($record->isActive()) {
                false => 'opacity-50',
                default => null,
            })
            ->defaultSort('is_global', 'desc');
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
