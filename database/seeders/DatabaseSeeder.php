<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\CategoryService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'demo@catwang.com'],
            [
                'name' => 'Budi Santoso',
                'password' => bcrypt('password123'),
            ]
        );

        CategoryService::seedDefaultsForUser($user);

        // Fetch categories for demo transactions
        $categories = $user->categories()->get()->keyBy('name');

        // Sample income
        if ($cat = $categories->get('Gaji')) {
            $user->transactions()->create([
                'category_id' => $cat->id,
                'type' => 'income',
                'amount' => 8500000,
                'description' => 'Gaji Bulanan PT Maju Jaya',
                'transaction_date' => now()->startOfMonth()->addDays(1),
            ]);
        }

        if ($cat = $categories->get('Freelance')) {
            $user->transactions()->create([
                'category_id' => $cat->id,
                'type' => 'income',
                'amount' => 2500000,
                'description' => 'Desain Landing Page Client ABC',
                'transaction_date' => now()->startOfMonth()->addDays(5),
            ]);
        }

        // Sample expenses
        $expenses = [
            ['Makanan', 45000, 'Makan Siang Nasi Padang', now()->startOfMonth()->addDays(2)],
            ['Transportasi', 150000, 'Bensin Mobil & Tol', now()->startOfMonth()->addDays(3)],
            ['Internet', 350000, 'Tagihan Indihome Wifi', now()->startOfMonth()->addDays(4)],
            ['Belanja', 420000, 'Belanja Mingguan Supermarket', now()->startOfMonth()->addDays(6)],
            ['Hiburan', 75000, 'Nonton Bioskop Akhir Pekan', now()->startOfMonth()->addDays(7)],
            ['Makanan', 32000, 'Kopi Susu & Toast', now()],
        ];

        foreach ($expenses as [$catName, $amount, $desc, $date]) {
            if ($cat = $categories->get($catName)) {
                $user->transactions()->create([
                    'category_id' => $cat->id,
                    'type' => 'expense',
                    'amount' => $amount,
                    'description' => $desc,
                    'transaction_date' => $date,
                ]);
            }
        }

        // Previous month data for comparison
        $prevMonth = now()->subMonth();
        if ($cat = $categories->get('Gaji')) {
            $user->transactions()->create([
                'category_id' => $cat->id,
                'type' => 'income',
                'amount' => 8500000,
                'description' => 'Gaji Bulan Lalu',
                'transaction_date' => $prevMonth->copy()->startOfMonth()->addDays(1),
            ]);
        }
        if ($cat = $categories->get('Makanan')) {
            $user->transactions()->create([
                'category_id' => $cat->id,
                'type' => 'expense',
                'amount' => 1250000,
                'description' => 'Akumulasi Makanan Bulan Lalu',
                'transaction_date' => $prevMonth->copy()->startOfMonth()->addDays(15),
            ]);
        }
        if ($cat = $categories->get('Tagihan')) {
            $user->transactions()->create([
                'category_id' => $cat->id,
                'type' => 'expense',
                'amount' => 500000,
                'description' => 'Listrik & Air Bulan Lalu',
                'transaction_date' => $prevMonth->copy()->startOfMonth()->addDays(10),
            ]);
        }

        // Budgets
        if ($cat = $categories->get('Makanan')) {
            $user->budgets()->create([
                'category_id' => $cat->id,
                'amount' => 1500000,
                'period' => 'monthly',
            ]);
        }
        if ($cat = $categories->get('Hiburan')) {
            $user->budgets()->create([
                'category_id' => $cat->id,
                'amount' => 500000,
                'period' => 'monthly',
            ]);
        }

        // Saving Goal
        $user->savingGoals()->create([
            'name' => 'Dana Darurat 6 Bulan',
            'target_amount' => 15000000,
            'current_amount' => 4500000,
            'target_date' => now()->addMonths(6)->toDateString(),
            'description' => 'Tabungan darurat di rekening terpisah',
        ]);
    }
}
