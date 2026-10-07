<?php

use App\Models\User;
use App\Services\CategoryService;

test('dashboard displays calculated balances accurately', function () {
    $user = User::factory()->create();
    CategoryService::seedDefaultsForUser($user);

    $catInc = $user->categories()->where('type', 'income')->first();
    $catExp = $user->categories()->where('type', 'expense')->first();

    $user->transactions()->create([
        'category_id' => $catInc->id,
        'type' => 'income',
        'amount' => 10000000,
        'description' => 'Gaji Penuh',
        'transaction_date' => now()->toDateString(),
    ]);

    $user->transactions()->create([
        'category_id' => $catExp->id,
        'type' => 'expense',
        'amount' => 2500000,
        'description' => 'Sewa Kos',
        'transaction_date' => now()->toDateString(),
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('7.500.000'); // 10jt - 2.5jt
    $response->assertSee('10.000.000');
    $response->assertSee('2.500.000');
});

test('user can access reports and export CSV', function () {
    $user = User::factory()->create();
    CategoryService::seedDefaultsForUser($user);

    $cat = $user->categories()->first();
    $user->transactions()->create([
        'category_id' => $cat->id,
        'type' => 'income',
        'amount' => 500000,
        'description' => 'Freelance Desain Logo',
        'transaction_date' => now()->toDateString(),
    ]);

    $response = $this->actingAs($user)->get('/reports');
    $response->assertStatus(200);
    $response->assertSee('Freelance Desain Logo');

    $exportResponse = $this->actingAs($user)->get('/reports/export?year='.now()->year.'&month='.now()->month);
    $exportResponse->assertStatus(200);
    $exportResponse->assertHeader('content-type', 'text/csv; charset=UTF-8');
});
