<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Post;
use App\Models\Game;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Aantal gebruikers', User::count())
                ->icon('heroicon-o-users')
                ->color('success'),

            Stat::make('Aantal posts', Post::count())
                ->icon('heroicon-o-document-text')
                ->color('primary'),

            Stat::make('Aantal wedstrijden', Game::count())
                ->icon('heroicon-o-calendar')
                ->color('danger'),
        ];
    }
}
