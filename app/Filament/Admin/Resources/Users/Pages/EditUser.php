<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use App\Models\AdminActivities;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        AdminActivities::create([
            'user_id' => Auth::id(),
            'created_at' => now(),
            'subject_type' => User::class,
            'subject_id' => $this->record->id,
            'action' => 'edited user record',
        ]);
    
        logger('Admin activity logged for user ID: '.$this->record->id);
    }
}
