<?php

use App\Http\Middleware\EnsureAdminUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

beforeEach(function () {
    Route::middleware(EnsureAdminUser::class)
        ->get('/admin-only-test', fn () => 'OK');
});

test('non admin users cannot access admin panel', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $response = $this->actingAs($user)
        ->get('/admin-only-test');

    $response->assertForbidden();
});

test('admin users can pass through admin middleware', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($admin)
        ->get('/admin-only-test');

    $response->assertOk()
        ->assertSee('OK');
});
