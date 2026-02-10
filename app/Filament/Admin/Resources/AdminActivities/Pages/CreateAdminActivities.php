<?php

namespace App\Filament\Admin\Resources\AdminActivities\Pages;

use App\Filament\Admin\Resources\AdminActivities\AdminActivitiesResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\AdminActivities;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateAdminActivities extends CreateRecord
{
    protected static string $resource = AdminActivitiesResource::class;

    protected function afterCreate(): void
    {
        AdminActivities::create([
            'user_id'   => Auth::id(),
            'subject_type'  => User::class,
            'subject_id' => $this->record->id,
            'action' => 'user_created',
        ]);
    }
}
