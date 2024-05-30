<?php

namespace App\Filament\Resources\ApproverResource\Pages;

use App\Filament\Resources\ApproverResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApprovers extends ListRecords
{
    protected static string $resource = ApproverResource::class;

    protected function getHeaderActions(): array
    {
        return [
//          CreateAction::make()->label('Tambah Pimpinan'),
        ];
    }
}
