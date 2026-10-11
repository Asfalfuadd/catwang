<?php

use App\Models\User;

test('user can view saving goals index', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('savings.index'));

    $response->assertOk();
    $response->assertSee('Target Tabungan');
});

test('user can create a saving goal', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('savings.store'), [
        'name' => 'Dana Darurat 2026',
        'target_amount' => 10000000,
        'current_amount' => 2000000,
        'target_date' => now()->addMonths(6)->toDateString(),
        'description' => 'Target tabungan untuk dana darurat',
    ]);

    $response->assertRedirect(route('savings.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('saving_goals', [
        'user_id' => $user->id,
        'name' => 'Dana Darurat 2026',
        'target_amount' => 10000000,
        'current_amount' => 2000000,
    ]);
});

test('user can add funds to saving goal', function () {
    $user = User::factory()->create();

    $goal = $user->savingGoals()->create([
        'name' => 'Beli Laptop',
        'target_amount' => 15000000,
        'current_amount' => 5000000,
    ]);

    $response = $this->actingAs($user)->post(route('savings.add', $goal), [
        'amount' => 1000000,
    ]);

    $response->assertRedirect(route('savings.index'));
    $response->assertSessionHas('success');

    expect($goal->fresh()->current_amount)->toEqual('6000000.00');
});

test('user cannot add funds to another users saving goal', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $goal = $otherUser->savingGoals()->create([
        'name' => 'Beli Rumah',
        'target_amount' => 500000000,
        'current_amount' => 10000000,
    ]);

    $response = $this->actingAs($user)->post(route('savings.add', $goal), [
        'amount' => 1000000,
    ]);

    $response->assertForbidden();
});

test('user can delete their own saving goal', function () {
    $user = User::factory()->create();

    $goal = $user->savingGoals()->create([
        'name' => 'Liburan Jepang',
        'target_amount' => 25000000,
        'current_amount' => 5000000,
    ]);

    $response = $this->actingAs($user)->delete(route('savings.destroy', $goal));

    $response->assertRedirect(route('savings.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('saving_goals', [
        'id' => $goal->id,
    ]);
});

test('user cannot delete another users saving goal', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $goal = $otherUser->savingGoals()->create([
        'name' => 'Beli Mobil',
        'target_amount' => 200000000,
        'current_amount' => 20000000,
    ]);

    $response = $this->actingAs($user)->delete(route('savings.destroy', $goal));

    $response->assertForbidden();
});
