<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReservations extends ListRecords
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
          CreateAction::make()->label('Buat Pesanan'),
        ];
    }
}
