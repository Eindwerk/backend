<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('inviteAdmin')
                ->label('Nodig een admin uit')
                ->icon('heroicon-o-envelope')
                ->url(fn() => UserResource::getUrl('invite'))
                ->visible(fn() => \Illuminate\Support\Facades\Auth::user()?->role === 'super_admin')
                ->color('primary'),
        ];
    }
}
