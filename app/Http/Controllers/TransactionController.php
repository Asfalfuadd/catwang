<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = $user->transactions()->with('category');

        // Filter by Type
        if ($request->filled('type') && in_array($request->type, ['income', 'expense'])) {
            $query->where('type', $request->type);
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('month') && $request->filled('year')) {
            $query->whereYear('transaction_date', $request->year)
                ->whereMonth('transaction_date', $request->month);
        } elseif ($request->filled('month')) {
            $query->whereMonth('transaction_date', $request->month);
        } elseif ($request->filled('year')) {
            $query->whereYear('transaction_date', $request->year);
        }

        // Search in Description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%'.$request->search.'%');
        }

        // Clone query for totals of filtered data
        $totalsQuery = clone $query;
        $filteredIncome = (float) (clone $totalsQuery)->where('type', 'income')->sum('amount');
        $filteredExpense = (float) (clone $totalsQuery)->where('type', 'expense')->sum('amount');
        $filteredNet = $filteredIncome - $filteredExpense;

        $transactions = $query->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $categories = $user->categories()->orderBy('name')->get();

        return view('transactions.index', compact(
            'transactions',
            'categories',
            'filteredIncome',
            'filteredExpense',
            'filteredNet'
        ));
    }

    public function create(Request $request): View
    {
        $user = Auth::user();
        $categories = $user->categories()->orderBy('name')->get();
        $defaultType = $request->get('type', 'expense');

        return view('transactions.create', compact('categories', 'defaultType'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'category_id' => [
                'required',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($user) {
                    if (! $user->categories()->where('id', $value)->exists()) {
                        $fail('Kategori yang dipilih tidak valid.');
                    }
                },
            ],
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['required', 'string', 'max:1000'],
            'transaction_date' => ['required', 'date'],
        ]);

        $user->transactions()->create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function edit(Transaction $transaction): View|RedirectResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $user = Auth::user();
        $categories = $user->categories()->orderBy('name')->get();

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $user = Auth::user();

        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'category_id' => [
                'required',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($user) {
                    if (! $user->categories()->where('id', $value)->exists()) {
                        $fail('Kategori yang dipilih tidak valid.');
                    }
                },
            ],
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['required', 'string', 'max:1000'],
            'transaction_date' => ['required', 'date'],
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
