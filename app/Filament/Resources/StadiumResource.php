<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StadiumResource\Pages;
use App\Models\Stadium;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class StadiumResource extends Resource
{
    protected static ?string $model = Stadium::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Stadions';
    protected static ?string $pluralLabel = 'Stadions';
    protected static ?string $navigationGroup = 'Beheer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Naam')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('city')
                    ->label('Stad')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('capacity')
                    ->label('Capaciteit')
                    ->numeric()
                    ->required(),

                Forms\Components\FileUpload::make('image_url')
                    ->label('Afbeelding')
                    ->directory('stadiums')
                    ->image()
                    ->imagePreviewHeight('100')
                    ->maxSize(2048)
                    ->nullable(),

                Forms\Components\TextInput::make('location.lat')
                    ->label('Latitude')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('location.lng')
                    ->label('Longitude')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Afbeelding')
                    ->disk('public'),

                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Naam')->searchable(),
                Tables\Columns\TextColumn::make('city')->label('Stad')->searchable(),
                Tables\Columns\TextColumn::make('capacity')->label('Capaciteit')->sortable(),
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
            'index' => Pages\ListStadia::route('/'),
            'create' => Pages\CreateStadium::route('/create'),
            'edit' => Pages\EditStadium::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin']);
    }

    public static function canView($record): bool
    {
        return static::canViewAny();
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->role === 'super_admin';
    }

    public static function canEdit($record): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin']);
    }

    public static function canDelete($record): bool
    {
        return Auth::check() && Auth::user()->role === 'super_admin';
    }

    public static function canDeleteAny(): bool
    {
        return static::canDelete(null);
    }
}
