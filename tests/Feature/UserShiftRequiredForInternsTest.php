<?php

use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('Shift is a required field for interns', function () {
    Livewire::test(CreateUser::class)
        ->set('data.role', 'intern')       // role must be intern
        ->set('data.shift_id', null)       // leave shift empty
        ->call('create')                   // trigger validation
        ->assertHasErrors(['data.shift_id' => 'required']); // check required error
});
test('Shift is not a required field for admins', function () {
    Livewire::test(CreateUser::class)
        ->set('data.role', 'admin')
        ->set('data.shift_id', null)
        ->call('create')
        ->assertHasNoErrors(['data.shift_id']);
});
