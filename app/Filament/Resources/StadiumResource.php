<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StadiumResource\Pages;
use App\Models\Stadium;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

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
                ->required()
                ->searchable(),

            Forms\Components\TextInput::make('latitude')
                ->label('Latitude')
                ->required()
                ->numeric(),

            Forms\Components\TextInput::make('longitude')
                ->label('Longitude')
                ->required()
                ->numeric(),

            Forms\Components\FileUpload::make('profile_image')
                ->label('Profielafbeelding')
                ->disk('public')
                ->directory('uploads/stadiums/profile-image')
                ->image()
                ->imagePreviewHeight(100)
                ->visibility('public')
                ->maxSize(2048)
                ->nullable()
                ->preserveFilenames(false)
                ->getUploadedFileNameForStorageUsing(
                    fn(UploadedFile $file): string =>
                    Str::random(40) . '.' . $file->getClientOriginalExtension()
                )
                // Verwijder oude afbeelding bij upload
                ->dehydrateStateUsing(function ($state, callable $get, ?Model $record) {
                    if ($record && $state && $state !== $record->profile_image) {
                        if ($record->profile_image && File::exists(public_path($record->profile_image))) {
                            File::delete(public_path($record->profile_image));
                        }
                    }
                    return $state;
                }),

            Forms\Components\FileUpload::make('banner_image')
                ->label('Bannerafbeelding')
                ->disk('public')
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
                )
                ->dehydrateStateUsing(function ($state, callable $get, ?Model $record) {
                    if ($record && $state && $state !== $record->banner_image) {
                        if ($record->banner_image && File::exists(public_path($record->banner_image))) {
                            File::delete(public_path($record->banner_image));
                        }
                    }
                    return $state;
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_image')
                    ->label('Profielafbeelding')
                    ->disk('public')
                    ->visibility('public')
                    ->height(50)
                    ->getStateUsing(fn($record) => $record->profile_image ? env('APP_URL') . '/' . ltrim($record->profile_image, '/') : null),

                Tables\Columns\ImageColumn::make('banner_image')
                    ->label('Banner')
                    ->disk('public')
                    ->visibility('public')
                    ->height(50)
                    ->getStateUsing(fn($record) => $record->banner_image ? env('APP_URL') . '/' . ltrim($record->banner_image, '/') : null),

                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Naam')->searchable(),
                Tables\Columns\TextColumn::make('team.name')->label('Team')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('latitude')->label('Latitude'),
                Tables\Columns\TextColumn::make('longitude')->label('Longitude'),
                Tables\Columns\TextColumn::make('created_at')->label('Aangemaakt op')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->before(function ($records) {
                        foreach ($records as $stadium) {
                            if ($stadium->profile_image && File::exists(public_path($stadium->profile_image))) {
                                File::delete(public_path($stadium->profile_image));
                            }
                            if ($stadium->banner_image && File::exists(public_path($stadium->banner_image))) {
                                File::delete(public_path($stadium->banner_image));
                            }
                        }
                    }),
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
