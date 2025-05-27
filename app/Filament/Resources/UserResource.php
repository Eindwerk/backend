<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\FileUpload;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Gebruikers';
    protected static ?string $pluralLabel = 'Gebruikers';
    protected static ?string $navigationGroup = 'Beheer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('profile_image')
                    ->label('Profielfoto')
                    ->image()
                    ->directory('users')
                    ->imageEditor()
                    ->imagePreviewHeight('150')
                    ->maxSize(2048),

                Forms\Components\TextInput::make('name')
                    ->label('Naam')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('E-mailadres')
                    ->email()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\ImageColumn::make('profile_image')
                    ->label('Profielfoto')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')->label('Naam')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('E-mailadres')->searchable(),
                Tables\Columns\TextColumn::make('role')->label('Rol')->badge(),
                Tables\Columns\TextColumn::make('created_at')->label('Aangemaakt op')->dateTime()->sortable(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'invite' => Pages\InviteAdmin::route('/invite'),
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
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin']);
    }

    public static function canDeleteAny(): bool
    {
        return static::canDelete(null);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()?->role === 'super_admin';
    }
}
