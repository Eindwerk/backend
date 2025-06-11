<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\SelectFilter;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Teams';
    protected static ?string $pluralLabel = 'Teams';
    protected static ?string $navigationGroup = 'Beheer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Naam')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('league_id')
                    ->label('Competitie')
                    ->relationship('league', 'name')
                    ->required()
                    ->searchable(),

                Forms\Components\FileUpload::make('logo_url')
                    ->label('Logo')
                    ->directory('teams')
                    ->image()
                    ->imagePreviewHeight('100')
                    ->maxSize(1024)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_url')
                    ->label('Logo')
                    ->disk('public'),

                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Naam')->searchable(),
                Tables\Columns\TextColumn::make('league.name')->label('Competitie')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Aangemaakt op')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('league_id')
                    ->label('Competitie')
                    ->relationship('league', 'name')
                    ->searchable(),
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
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
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
