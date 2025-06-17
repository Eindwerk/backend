<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StadiumResource\Pages;
use App\Models\Stadium;
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

class StadiumResource extends Resource
{
    protected static ?string $model = Stadium::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Stadions';
    protected static ?string $pluralLabel = 'Stadions';
    protected static ?string $navigationGroup = 'Beheer';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Naam')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('team_id')
                ->label('Team')
                ->relationship('team', 'name')
                ->searchable()
                ->required(),

            Forms\Components\FileUpload::make('profile_image')
                ->label('Logo')
                ->disk('uploads')
                ->directory('uploads/stadiums/profile-image')
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
                ->directory('uploads/stadiums/banner-image')
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

            // Gebruik hier latitude en altitude, niet location.latitude
            Forms\Components\TextInput::make('latitude')
                ->label('Latitude')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('altitude')
                ->label('Altitude')
                ->numeric()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_image')
                    ->label('Logo')
                    ->disk('uploads')
                    ->visibility('public')
                    ->circular()
                    ->height(50)
                    ->getStateUsing(
                        fn($record) =>
                        $record->profile_image ? env('APP_URL') . '/' . ltrim($record->profile_image, '/') : null
                    ),

                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Naam')->searchable(),
                Tables\Columns\TextColumn::make('team.name')->label('Team')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Aangemaakt op')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('team_id')
                    ->label('Team')
                    ->relationship('team', 'name')
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
            'index' => Pages\ListStadia::route('/'),
            'create' => Pages\CreateStadium::route('/create'),
            'edit' => Pages\EditStadium::route('/{record}/edit'),
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
