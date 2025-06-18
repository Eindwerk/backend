<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $navigationLabel = 'Posts';
    protected static ?string $pluralLabel = 'Posts';
    protected static ?string $navigationGroup = 'Beheer';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('Gebruiker')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('game_id')
                ->label('Wedstrijd')
                ->relationship('game', 'id')
                ->searchable()
                ->required(),

            Forms\Components\FileUpload::make('image')
                ->label('Afbeelding')
                ->image()
                ->disk('s3')                   // S3 disk gebruiken
                ->directory('posts')           // optionele subfolder
                ->required(false)
                ->maxSize(2048),               // max 2MB
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Gebruiker')->searchable(),
                Tables\Columns\TextColumn::make('game.id')->label('Wedstrijd'),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Afbeelding')
                    ->disk('s3')               // S3 disk gebruiken
                    ->height(50)
                    ->width(50)
                    ->square(),
                Tables\Columns\TextColumn::make('created_at')->label('Aangemaakt op')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return self::isAdmin();
    }

    public static function canView(Model $record): bool
    {
        return self::isAdmin();
    }

    public static function canCreate(): bool
    {
        return false; // Pas aan naar true als aanmaken via Filament gewenst is
    }

    public static function canEdit(Model $record): bool
    {
        return false; // Pas aan naar true als bewerken via Filament gewenst is
    }

    public static function canDelete(Model $record): bool
    {
        return self::isSuperAdmin();
    }

    public static function canDeleteAny(): bool
    {
        return self::isSuperAdmin();
    }

    protected static function isAdmin(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin']);
    }

    protected static function isSuperAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'super_admin';
    }
}
