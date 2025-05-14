<?php

namespace App\Filament\Resources\StadiumResource\Pages;

use App\Filament\Resources\StadiumResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStadium extends EditRecord
{
    protected static string $resource = StadiumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
