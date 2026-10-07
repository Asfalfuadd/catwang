<?php

use App\Models\User;
use App\Services\CategoryService;

test('guest is redirected to login when accessing transactions', function () {
    $response = $this->get('/transactions');
    $response->assertRedirect('/login');
});

test('user can view their transaction list and not other users transactions', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    CategoryService::seedDefaultsForUser($userA);
    CategoryService::seedDefaultsForUser($userB);

    $catA = $userA->categories()->first();
    $catB = $userB->categories()->first();

    $txA = $userA->transactions()->create([
        'category_id' => $catA->id,
        'type' => 'income',
        'amount' => 5000000,
        'description' => 'Pendapatan User A',
        'transaction_date' => now()->toDateString(),
    ]);

    $txB = $userB->transactions()->create([
        'category_id' => $catB->id,
        'type' => 'expense',
        'amount' => 200000,
        'description' => 'Pengeluaran Rahasia User B',
        'transaction_date' => now()->toDateString(),
    ]);

    $response = $this->actingAs($userA)->get('/transactions');

    $response->assertStatus(200);
    $response->assertSee('Pendapatan User A');
    $response->assertDontSee('Pengeluaran Rahasia User B');
});

test('user can create a transaction', function () {
    $user = User::factory()->create();
    CategoryService::seedDefaultsForUser($user);
    $category = $user->categories()->where('type', 'expense')->first();

    $response = $this->actingAs($user)->post('/transactions', [
        'type' => 'expense',
        'category_id' => $category->id,
        'amount' => 75000,
        'description' => 'Beli kopi dan roti',
        'transaction_date' => now()->toDateString(),
    ]);

    $response->assertRedirect(route('transactions.index'));
    $this->assertDatabaseHas('transactions', [
        'user_id' => $user->id,
        'description' => 'Beli kopi dan roti',
        'amount' => 75000,
    ]);
});

test('user can update their transaction', function () {
    $user = User::factory()->create();
    CategoryService::seedDefaultsForUser($user);
    $category = $user->categories()->where('type', 'expense')->first();

    $tx = $user->transactions()->create([
        'category_id' => $category->id,
        'type' => 'expense',
        'amount' => 50000,
        'description' => 'Deskripsi Lama',
        'transaction_date' => now()->toDateString(),
    ]);

    $response = $this->actingAs($user)->put("/transactions/{$tx->id}", [
        'type' => 'expense',
        'category_id' => $category->id,
        'amount' => 60000,
        'description' => 'Deskripsi Baru',
        'transaction_date' => now()->toDateString(),
    ]);

    $response->assertRedirect(route('transactions.index'));
    expect($tx->fresh()->description)->toBe('Deskripsi Baru');
    expect((float) $tx->fresh()->amount)->toBe(60000.0);
});

test('user can delete their transaction', function () {
    $user = User::factory()->create();
    CategoryService::seedDefaultsForUser($user);
    $category = $user->categories()->first();

    $tx = $user->transactions()->create([
        'category_id' => $category->id,
        'type' => 'income',
        'amount' => 100000,
        'description' => 'Bonus Sementara',
        'transaction_date' => now()->toDateString(),
    ]);

    $response = $this->actingAs($user)->delete("/transactions/{$tx->id}");

    $response->assertRedirect(route('transactions.index'));
    $this->assertDatabaseMissing('transactions', ['id' => $tx->id]);
});
