<?php

use App\Models\User;
use App\Services\CategoryService;

test('user can view and create category', function () {
    $user = User::factory()->create();
    CategoryService::seedDefaultsForUser($user);

    $response = $this->actingAs($user)->post('/categories', [
        'name' => 'Kripto & Saham',
        'type' => 'income',
        'color' => '#FFEB3B',
        'icon' => 'coins',
    ]);

    $response->assertRedirect(route('categories.index'));
    $this->assertDatabaseHas('categories', [
        'user_id' => $user->id,
        'name' => 'Kripto & Saham',
        'type' => 'income',
    ]);
});

test('user cannot delete category that has transactions', function () {
    $user = User::factory()->create();
    CategoryService::seedDefaultsForUser($user);
    $category = $user->categories()->first();

    $user->transactions()->create([
        'category_id' => $category->id,
        'type' => $category->type,
        'amount' => 150000,
        'description' => 'Transaksi Terikat',
        'transaction_date' => now()->toDateString(),
    ]);

    $response = $this->actingAs($user)->delete("/categories/{$category->id}");

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});

test('user can create and update budget', function () {
    $user = User::factory()->create();
    CategoryService::seedDefaultsForUser($user);
    $expenseCategory = $user->categories()->where('type', 'expense')->first();

    $response = $this->actingAs($user)->post('/budgets', [
        'category_id' => $expenseCategory->id,
        'amount' => 1500000,
        'period' => 'monthly',
    ]);

    $response->assertRedirect(route('budgets.index'));
    $this->assertDatabaseHas('budgets', [
        'user_id' => $user->id,
        'category_id' => $expenseCategory->id,
        'amount' => 1500000,
    ]);
});

test('user can create saving goal and add funds', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/savings', [
        'name' => 'Beli Laptop Baru',
        'target_amount' => 15000000,
        'current_amount' => 2000000,
    ]);

    $response->assertRedirect(route('savings.index'));
    $goal = $user->savingGoals()->where('name', 'Beli Laptop Baru')->first();
    expect($goal)->not->toBeNull();

    $depositResponse = $this->actingAs($user)->post("/savings/{$goal->id}/add", [
        'amount' => 1000000,
    ]);

    $depositResponse->assertRedirect(route('savings.index'));
    expect((float) $goal->fresh()->current_amount)->toBe(3000000.0);
});
