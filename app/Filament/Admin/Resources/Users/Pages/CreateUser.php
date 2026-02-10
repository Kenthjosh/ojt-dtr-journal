<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\AdminActivities;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Throwable;
class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): User
    {
        try {
            /** @var User $user */
            $user = parent::handleRecordCreation($data);

            return $user;
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            throw ValidationException::withMessages([
                'email' => 'User creation failed. Please try again.',
            ]);
        }
    }

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
