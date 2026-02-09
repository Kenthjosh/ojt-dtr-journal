<?php

use App\Http\Middleware\EnsureAdminUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    Route::middleware(EnsureAdminUser::class)
        ->get('/admin-only-test', fn () => 'OK');
});

test('non admin users cannot access admin panel', function () {
    /** @var User $user */
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    actingAs($user);
    $response = get('/admin-only-test');

    $response->assertForbidden();
});

test('admin users can pass through admin middleware', function () {
    /** @var User $admin */
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    actingAs($admin);
    $response = get('/admin-only-test');

    $response->assertOk()
        ->assertSee('OK');
});
