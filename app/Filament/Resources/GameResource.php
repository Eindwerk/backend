<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameResource\Pages;
use App\Models\Game;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GameResource extends Resource
{
    protected static ?string $model = Game::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Wedstrijden';
    protected static ?string $pluralLabel = 'Wedstrijden';
    protected static ?string $navigationGroup = 'Beheer';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('stadium_id')
                ->label('Stadion')
                ->relationship('stadium', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('home_team_id')
                ->label('Thuisploeg')
                ->relationship('homeTeam', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('away_team_id')
                ->label('Uitploeg')
                ->relationship('awayTeam', 'name')
                ->searchable()
                ->required(),

            Forms\Components\DateTimePicker::make('match_date')
                ->label('Datum & tijd')
                ->required(),

            Forms\Components\TextInput::make('home_score')
                ->label('Score thuisploeg')
                ->numeric()
                ->minValue(0),

            Forms\Components\TextInput::make('away_score')
                ->label('Score uitploeg')
                ->numeric()
                ->minValue(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),

            Tables\Columns\TextColumn::make('stadium.name')
                ->label('Stadion')
                ->searchable(),

            Tables\Columns\TextColumn::make('homeTeam.name')
                ->label('Thuisploeg')
                ->searchable(),

            Tables\Columns\TextColumn::make('awayTeam.name')
                ->label('Uitploeg')
                ->searchable(),

            Tables\Columns\TextColumn::make('match_date')
                ->label('Datum')
                ->dateTime()
                ->sortable(),

            Tables\Columns\TextColumn::make('home_score')
                ->label('Thuis')
                ->sortable(),

            Tables\Columns\TextColumn::make('away_score')
                ->label('Uit')
                ->sortable(),
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
            'index' => Pages\ListGames::route('/'),
            'create' => Pages\CreateGame::route('/create'),
            'edit' => Pages\EditGame::route('/{record}/edit'),
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
        return self::isAdmin();
    }

    public static function canEdit(Model $record): bool
    {
        return self::isAdmin();
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
