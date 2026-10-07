<?php

use App\Models\User;

test('landing page loads successfully', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('CatWang');
});

test('login page is accessible', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
    $response->assertSee('Masuk');
});

test('user can register and gets default categories seeded', function () {
    $response = $this->post('/register', [
        'name' => 'Fulan bin Fulan',
        'email' => 'fulan@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();

    $user = User::where('email', 'fulan@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->categories()->count())->toBeGreaterThan(10);
});

test('user can login successfully', function () {
    $user = User::factory()->create([
        'email' => 'user@test.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => 'user@test.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('user can logout successfully', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});
