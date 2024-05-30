<?php

namespace App\Filament\Resources\ApproverResource\Pages;

use App\Filament\Resources\ApproverResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditApprover extends EditRecord
{
    protected static string $resource = ApproverResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
