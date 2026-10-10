<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

test('guest cannot access profile settings', function () {
    $response = $this->get(route('profile.edit'));
    $response->assertRedirect(route('login'));
});

test('authenticated user can view profile edit page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('profile.edit'));

    $response->assertStatus(200);
    $response->assertSee('Profil & Pengaturan Akun', false);
    $response->assertSee($user->name);
    $response->assertSee($user->email);
});

test('user can update username and email', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);

    $response = $this->actingAs($user)->patch(route('profile.update'), [
        'name' => 'New Username',
        'email' => 'updated@example.com',
    ]);

    $response->assertSessionHas('success');
    $user->refresh();

    expect($user->name)->toBe('New Username');
    expect($user->email)->toBe('updated@example.com');
});

test('user cannot take email already used by another user', function () {
    User::factory()->create(['email' => 'taken@example.com']);
    $user = User::factory()->create(['email' => 'user@example.com']);

    $response = $this->actingAs($user)->patch(route('profile.update'), [
        'name' => 'My Name',
        'email' => 'taken@example.com',
    ]);

    $response->assertSessionHasErrors('email');
    $user->refresh();

    expect($user->email)->toBe('user@example.com');
});

test('user can upload profile avatar', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $file = UploadedFile::fake()->image('avatar.jpg', 200, 200);

    $response = $this->actingAs($user)->post(route('profile.avatar.update'), [
        'avatar' => $file,
    ]);

    $response->assertSessionHas('success');
    $user->refresh();

    expect($user->avatar)->not->toBeNull();
    Storage::disk('public')->assertExists($user->avatar);
});

test('user can upload cropped profile avatar via base64', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

    $response = $this->actingAs($user)->post(route('profile.avatar.update'), [
        'avatar_base64' => $base64,
    ]);

    $response->assertSessionHas('success');
    $user->refresh();

    expect($user->avatar)->not->toBeNull();
    Storage::disk('public')->assertExists($user->avatar);
});

test('user can upload cropped profile avatar via base64 even if avatar field is present or null', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

    $response = $this->actingAs($user)->post(route('profile.avatar.update'), [
        'avatar' => null,
        'avatar_base64' => $base64,
    ]);

    $response->assertSessionHas('success');
    $user->refresh();

    expect($user->avatar)->not->toBeNull();
    Storage::disk('public')->assertExists($user->avatar);
});

test('user can delete profile avatar', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'avatar' => 'avatars/sample.jpg',
    ]);
    Storage::disk('public')->put('avatars/sample.jpg', 'fake content');

    $response = $this->actingAs($user)->delete(route('profile.avatar.delete'));

    $response->assertSessionHas('success');
    $user->refresh();

    expect($user->avatar)->toBeNull();
    Storage::disk('public')->assertMissing('avatars/sample.jpg');
});

test('user can update password with current password confirmation', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password-123'),
    ]);

    $response = $this->actingAs($user)->put(route('profile.password.update'), [
        'current_password' => 'old-password-123',
        'password' => 'new-secure-password-456',
        'password_confirmation' => 'new-secure-password-456',
    ]);

    $response->assertSessionHas('success');
    $user->refresh();

    expect(Hash::check('new-secure-password-456', $user->password))->toBeTrue();
});

test('user can update theme preference', function () {
    $user = User::factory()->create([
        'theme' => 'light',
    ]);

    $response = $this->actingAs($user)->post(route('profile.theme.update'), [
        'theme' => 'dark',
    ]);

    $response->assertSessionHas('success');
    $user->refresh();

    expect($user->theme)->toBe('dark');
});

test('user can update theme preference via ajax json', function () {
    $user = User::factory()->create([
        'theme' => 'light',
    ]);

    $response = $this->actingAs($user)->postJson(route('profile.theme.update'), [
        'theme' => 'dark',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'success',
        'theme' => 'dark',
    ]);

    $user->refresh();
    expect($user->theme)->toBe('dark');
});

test('dashboard has total balance card with eye toggle component', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Total Saldo');
    $response->assertSee('catwang_show_balance');
    $response->assertSee('••••••••');
});
