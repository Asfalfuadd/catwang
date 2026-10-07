<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Total Balance (All time)
        $totalIncomeAllTime = (float) $user->transactions()
            ->where('type', 'income')
            ->sum('amount');

        $totalExpenseAllTime = (float) $user->transactions()
            ->where('type', 'expense')
            ->sum('amount');

        $totalBalance = $totalIncomeAllTime - $totalExpenseAllTime;

        // Current Month stats
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $monthIncome = (float) $user->transactions()
            ->where('type', 'income')
            ->whereBetween('transaction_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->sum('amount');

        $monthExpense = (float) $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->sum('amount');

        $monthBalance = $monthIncome - $monthExpense;

        $monthTransactionCount = $user->transactions()
            ->whereBetween('transaction_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->count();

        // Recent 6 transactions
        $recentTransactions = $user->transactions()
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        // Category breakdown for current month (expense only)
        $categoryBreakdown = $user->transactions()
            ->with('category')
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get();

        $categoryChartLabels = [];
        $categoryChartData = [];
        $categoryChartColors = [];

        foreach ($categoryBreakdown as $item) {
            $categoryChartLabels[] = $item->category?->name ?? 'Lainnya';
            $categoryChartData[] = (float) $item->total;
            $categoryChartColors[] = $item->category?->color ?? '#FF5252';
        }

        // 6 months trend data (Income vs Expense)
        $monthlyTrendLabels = [];
        $monthlyTrendIncome = [];
        $monthlyTrendExpense = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth()->toDateString();
            $monthEnd = $month->copy()->endOfMonth()->toDateString();

            $monthlyTrendLabels[] = $month->isoFormat('MMM YYYY');

            $inc = (float) $user->transactions()
                ->where('type', 'income')
                ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                ->sum('amount');

            $exp = (float) $user->transactions()
                ->where('type', 'expense')
                ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                ->sum('amount');

            $monthlyTrendIncome[] = $inc;
            $monthlyTrendExpense[] = $exp;
        }

        // Active saving goals (up to 2 for preview widget)
        $savingGoals = $user->savingGoals()->take(2)->get();

        // Budgets for current month
        $budgets = $user->budgets()->with('category')->take(3)->get();
        foreach ($budgets as $budget) {
            $spent = (float) $user->transactions()
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereBetween('transaction_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
                ->sum('amount');
            $budget->spent = $spent;
            $budget->percentage = $budget->amount > 0 ? min(100, round(($spent / $budget->amount) * 100)) : 0;
        }

        return view('dashboard.index', compact(
            'totalBalance',
            'monthIncome',
            'monthExpense',
            'monthBalance',
            'monthTransactionCount',
            'recentTransactions',
            'categoryChartLabels',
            'categoryChartData',
            'categoryChartColors',
            'monthlyTrendLabels',
            'monthlyTrendIncome',
            'monthlyTrendExpense',
            'savingGoals',
            'budgets'
        ));
    }
}
