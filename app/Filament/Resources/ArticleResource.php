<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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
                TextInput::make('title')
                    ->label('Название')
                    ->required()
                    ->maxLength(255)
                    ->live(debounce: 500)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', SlugService::createSlug(Article::class, 'slug', $state ?? ''))),
                TextInput::make('slug')
                    ->required()
                    ->label('Ссылка')
                    ->readonly()
                    ->maxLength(255),
                FileUpload::make('image')
                    ->label('Изображение')
                    ->image(),
                Section::make('Контент')
                    ->schema([
                        RichEditor::make('content')
                            ->fileAttachmentsDirectory('article-attachments')
                            ->label('Блок контента'),
                        RichEditor::make('content_advanced')
                            ->fileAttachmentsDirectory('article-attachments')
                            ->label('Блок контента'),
                    ])
                    ->columns(1),
                Toggle::make('is_active')
                    ->label('Активно')
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Автор')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Название')
                    ->sortable()
                    ->searchable(),
                ImageColumn::make('image')
                    ->label('Изображение'),
                ToggleColumn::make('is_active')
                    ->label('Активно')
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
            ->recordClasses(fn(Model $record) => match ($record->trashed()) {
                true => 'opacity-50',
                default => null,
            });
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
