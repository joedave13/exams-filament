<?php

namespace App\Filament\Resources\TryoutResource\Pages;

use App\Filament\Resources\TryoutResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTryout extends ViewRecord
{
    protected static string $resource = TryoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
