<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BudgetController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        $budgets = $user->budgets()->with('category')->get();

        $totalBudgeted = 0;
        $totalSpent = 0;

        foreach ($budgets as $budget) {
            $spent = (float) $user->transactions()
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $budget->spent = $spent;
            $budget->remaining = $budget->amount - $spent;
            $budget->percentage = $budget->amount > 0 ? min(100, round(($spent / $budget->amount) * 100)) : 0;
            $budget->is_over = $spent > $budget->amount;

            $totalBudgeted += $budget->amount;
            $totalSpent += $spent;
        }

        $expenseCategories = $user->categories()->where('type', 'expense')->orderBy('name')->get();

        return view('budgets.index', compact('budgets', 'totalBudgeted', 'totalSpent', 'expenseCategories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($user) {
                    if (! $user->categories()->where('id', $value)->where('type', 'expense')->exists()) {
                        $fail('Kategori pengeluaran tidak valid.');
                    }
                    if ($user->budgets()->where('category_id', $value)->exists()) {
                        $fail('Anggaran untuk kategori ini sudah dibuat sebelumnya.');
                    }
                },
            ],
            'amount' => ['required', 'numeric', 'min:1000'],
            'period' => ['required', 'string', 'in:monthly'],
        ]);

        $user->budgets()->create($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Anggaran berhasil ditetapkan!');
    }

    public function update(Request $request, Budget $budget): RedirectResponse
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000'],
        ]);

        $budget->update($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Jumlah anggaran berhasil diperbarui!');
    }

    public function destroy(Budget $budget): RedirectResponse
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $budget->delete();

        return redirect()->route('budgets.index')
            ->with('success', 'Anggaran berhasil dihapus.');
    }
}
