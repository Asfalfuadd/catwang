<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $categories = $user->categories()
            ->withCount('transactions')
            ->withSum('transactions', 'amount')
            ->orderBy('name')
            ->get();

        $incomeCategories = $categories->where('type', 'income');
        $expenseCategories = $categories->where('type', 'expense');

        return view('categories.index', compact('incomeCategories', 'expenseCategories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'type' => ['required', 'in:income,expense'],
            'color' => ['required', 'string', 'max:20'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        $user->categories()->create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'type' => ['required', 'in:income,expense'],
            'color' => ['required', 'string', 'max:20'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $transactionsCount = $category->transactions()->count();

        if ($transactionsCount > 0) {
            return redirect()->route('categories.index')
                ->with('error', "Kategori '{$category->name}' tidak dapat dihapus karena masih digunakan oleh {$transactionsCount} transaksi.");
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
