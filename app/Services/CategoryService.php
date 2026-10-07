<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;

class CategoryService
{
    /**
     * Seed default categories for a specific user.
     */
    public static function seedDefaultsForUser(User $user): void
    {
        $defaults = [
            // Incomes
            ['name' => 'Gaji', 'type' => 'income', 'color' => '#4CAF50', 'icon' => 'wallet'],
            ['name' => 'Freelance', 'type' => 'income', 'color' => '#2196F3', 'icon' => 'laptop'],
            ['name' => 'Bonus', 'type' => 'income', 'color' => '#FFEB3B', 'icon' => 'gift'],
            ['name' => 'Hadiah', 'type' => 'income', 'color' => '#E91E63', 'icon' => 'sparkles'],
            ['name' => 'Penjualan', 'type' => 'income', 'color' => '#FF9800', 'icon' => 'shopping-bag'],
            ['name' => 'Lainnya', 'type' => 'income', 'color' => '#9E9E9E', 'icon' => 'coins'],

            // Expenses
            ['name' => 'Makanan', 'type' => 'expense', 'color' => '#FF5252', 'icon' => 'utensils'],
            ['name' => 'Transportasi', 'type' => 'expense', 'color' => '#FF9800', 'icon' => 'car'],
            ['name' => 'Belanja', 'type' => 'expense', 'color' => '#9C27B0', 'icon' => 'shopping-cart'],
            ['name' => 'Tagihan', 'type' => 'expense', 'color' => '#F44336', 'icon' => 'receipt'],
            ['name' => 'Hiburan', 'type' => 'expense', 'color' => '#00BCD4', 'icon' => 'gamepad-2'],
            ['name' => 'Pendidikan', 'type' => 'expense', 'color' => '#3F51B5', 'icon' => 'graduation-cap'],
            ['name' => 'Kesehatan', 'type' => 'expense', 'color' => '#E91E63', 'icon' => 'heart-pulse'],
            ['name' => 'Internet', 'type' => 'expense', 'color' => '#009688', 'icon' => 'wifi'],
            ['name' => 'Lainnya', 'type' => 'expense', 'color' => '#607D8B', 'icon' => 'circle-dot'],
        ];

        foreach ($defaults as $cat) {
            Category::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'name' => $cat['name'],
                    'type' => $cat['type'],
                ],
                [
                    'color' => $cat['color'],
                    'icon' => $cat['icon'],
                ]
            );
        }
    }
}
