<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeagueResource\Pages;
use App\Models\League;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LeagueResource extends Resource
{
    protected static ?string $model = League::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Competities';
    protected static ?string $pluralModelLabel = 'Competities';
    protected static ?string $navigationGroup = 'Beheer';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Naam')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Naam')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Aangemaakt op')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeagues::route('/'),
            'create' => Pages\CreateLeague::route('/create'),
            'edit' => Pages\EditLeague::route('/{record}/edit'),
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
