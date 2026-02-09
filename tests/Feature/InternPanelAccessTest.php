<?php

use App\Http\Middleware\IsInternUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

beforeEach(function () {
    Route::middleware(IsInternUser::class)
        ->get('/intern-only-test', fn () => 'OK');
});

test('admin users cannot access intern panel', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($user)
        ->get('/intern-only-test');

    $response->assertForbidden();
});

test('intern users can pass through intern middleware', function () {
    $admin = User::factory()->create([
        'role' => 'intern',
    ]);

    $response = $this->actingAs($admin)
        ->get('/intern-only-test');

    $response->assertOk()
        ->assertSee('OK');
});
