<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\AdminActivities;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        AdminActivities::create([
            'user_id' => Auth::id(),
            'created_at' => now(),
            'subject_type' => User::class,
            'action' => 'created user',
            'subject_id' => $this->record->id,

        ]);
    }
}
