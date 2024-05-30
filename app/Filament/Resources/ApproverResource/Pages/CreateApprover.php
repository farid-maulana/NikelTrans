<?php

namespace App\Filament\Resources\ApproverResource\Pages;

use App\Filament\Resources\ApproverResource;
use App\Models\Approver;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateApprover extends CreateRecord
{
    protected static string $resource = ApproverResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['phone_number'] = str_replace('-', '', $data['phone_number']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['phone_number']),
            ]);

            $approver = Approver::create([
                'user_id' => $user->id,
                'phone_number' => $data['phone_number'],
                'title' => $data['title'],
            ]);

            DB::commit();
            return $approver;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
