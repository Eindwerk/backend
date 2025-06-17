<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Teams';
    protected static ?string $pluralLabel = 'Teams';
    protected static ?string $navigationGroup = 'Beheer';

    public static function form(Form $form): Form
    {
        return $form->schema([
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
                ->disk('uploads')
                ->directory('uploads/teams/profile-image')
                ->image()
                ->imagePreviewHeight(100)
                ->visibility('public')
                ->maxSize(1024)
                ->nullable()
                ->preserveFilenames(false)
                ->getUploadedFileNameForStorageUsing(
                    fn(UploadedFile $file): string =>
                    Str::random(40) . '.' . $file->getClientOriginalExtension()
                ),

            Forms\Components\FileUpload::make('banner_image')
                ->label('Banner')
                ->disk('uploads')
                ->directory('uploads/teams/banner-image')
                ->image()
                ->imagePreviewHeight(100)
                ->visibility('public')
                ->maxSize(4096)
                ->nullable()
                ->preserveFilenames(false)
                ->getUploadedFileNameForStorageUsing(
                    fn(UploadedFile $file): string =>
                    Str::random(40) . '.' . $file->getClientOriginalExtension()
                ),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_url')
                    ->label('Logo')
                    ->disk('uploads')
                    ->visibility('public')
                    ->height(50)
                    ->circular()
                    ->getStateUsing(
                        fn($record) =>
                        $record->logo_url ? env('APP_URL') . '/' . ltrim($record->logo_url, '/') : null
                    ),

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
