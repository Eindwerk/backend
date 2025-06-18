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
                ->label('Logo')
                ->disk('public')
                ->directory('stadiums/profile-image')
                ->image()
                ->imageEditor()
                ->imagePreviewHeight(100)
                ->visibility('public')
                ->preserveFilenames(false)
                ->dehydrated(true) // <-- toegevoegd voor consistentie
                ->required(false)
                ->rules(['image', 'max:1024']) // max 1MB
                ->getUploadedFileNameForStorageUsing(function (UploadedFile $file): string {
                    return md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
                })
                ->deleteUploadedFileUsing(function (?string $filePath) {
                    if ($filePath) {
                        $fullPath = storage_path('app/public/' . $filePath);
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    }
                })
                ->dehydrated(fn($state) => filled($state)),

            Forms\Components\FileUpload::make('banner_image')
                ->label('Banner')
                ->disk('public')
                ->directory('stadiums/banner-image')
                ->image()
                ->imageEditor()
                ->imagePreviewHeight(100)
                ->visibility('public')
                ->preserveFilenames(false)
                ->dehydrated(true)
                ->required(false)
                ->rules(['image', 'max:4096']) // max 4MB
                ->getUploadedFileNameForStorageUsing(function (UploadedFile $file): string {
                    return md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
                })
                ->deleteUploadedFileUsing(function (?string $filePath) {
                    if ($filePath) {
                        $fullPath = storage_path('app/public/' . $filePath);
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    }
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_image')
                    ->label('Logo')
                    ->disk('public')
                    ->visibility('public')
                    ->height(50)
                    ->circular(),

                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Naam')->searchable(),
                Tables\Columns\TextColumn::make('team.name')->label('Team')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('latitude')->label('Latitude'),
                Tables\Columns\TextColumn::make('longitude')->label('Longitude'),
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
