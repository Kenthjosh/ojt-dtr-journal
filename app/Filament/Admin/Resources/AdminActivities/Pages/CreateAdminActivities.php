<?php

namespace App\Filament\Admin\Resources\AdminActivities\Pages;

use App\Filament\Admin\Resources\AdminActivities\AdminActivitiesResource;
use App\Filament\Resources\Users;
use Filament\Resources\Pages\CreateRecord;
use App\Models\AdminActivities;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateAdminActivities extends CreateRecord
{
    protected static string $resource = AdminActivitiesResource::class;

}
