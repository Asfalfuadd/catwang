<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Default to current month and year if not specified
        $year = (int) $request->input('year', Carbon::now()->year);
        $month = (int) $request->input('month', Carbon::now()->month);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Previous Month for comparison
        $prevStartDate = $startDate->copy()->subMonth()->startOfMonth();
        $prevEndDate = $prevStartDate->copy()->endOfMonth();

        // Active period transactions
        $transactions = $user->transactions()
            ->with('category')
            ->whereBetween('transaction_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('transaction_date', 'desc')
            ->get();

        $totalIncome = (float) $transactions->where('type', 'income')->sum('amount');
        $totalExpense = (float) $transactions->where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;
        $transactionCount = $transactions->count();

        // Previous period metrics
        $prevIncome = (float) $user->transactions()
            ->where('type', 'income')
            ->whereBetween('transaction_date', [$prevStartDate->toDateString(), $prevEndDate->toDateString()])
            ->sum('amount');

        $prevExpense = (float) $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$prevStartDate->toDateString(), $prevEndDate->toDateString()])
            ->sum('amount');

        $prevBalance = $prevIncome - $prevExpense;

        $diffIncome = $totalIncome - $prevIncome;
        $diffExpense = $totalExpense - $prevExpense;
        $diffBalance = $netBalance - $prevBalance;

        // Expense by Category with percentages
        $expenseByCategory = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($items) use ($totalExpense) {
                $category = $items->first()->category;
                $sum = (float) $items->sum('amount');
                $pct = $totalExpense > 0 ? round(($sum / $totalExpense) * 100, 1) : 0;

                return [
                    'category' => $category,
                    'total' => $sum,
                    'percentage' => $pct,
                    'count' => $items->count(),
                ];
            })
            ->sortByDesc('total');

        // Income by Category with percentages
        $incomeByCategory = $transactions->where('type', 'income')
            ->groupBy('category_id')
            ->map(function ($items) use ($totalIncome) {
                $category = $items->first()->category;
                $sum = (float) $items->sum('amount');
                $pct = $totalIncome > 0 ? round(($sum / $totalIncome) * 100, 1) : 0;

                return [
                    'category' => $category,
                    'total' => $sum,
                    'percentage' => $pct,
                    'count' => $items->count(),
                ];
            })
            ->sortByDesc('total');

        return view('reports.index', compact(
            'year',
            'month',
            'startDate',
            'endDate',
            'totalIncome',
            'totalExpense',
            'netBalance',
            'transactionCount',
            'prevIncome',
            'prevExpense',
            'prevBalance',
            'diffIncome',
            'diffExpense',
            'diffBalance',
            'expenseByCategory',
            'incomeByCategory',
            'transactions'
        ));
    }

    public function export(Request $request): StreamedResponse
    {
        $user = Auth::user();

        $year = (int) $request->input('year', Carbon::now()->year);
        $month = (int) $request->input('month', Carbon::now()->month);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $transactions = $user->transactions()
            ->with('category')
            ->whereBetween('transaction_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('transaction_date', 'asc')
            ->get();

        $fileName = "laporan-catwang-{$year}-".str_pad((string) $month, 2, '0', STR_PAD_LEFT).'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($transactions) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['ID', 'Tanggal', 'Tipe', 'Kategori', 'Nominal (Rp)', 'Deskripsi']);

            foreach ($transactions as $t) {
                fputcsv($handle, [
                    $t->id,
                    $t->transaction_date->format('Y-m-d'),
                    $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                    $t->category?->name ?? 'Tanpa Kategori',
                    $t->amount,
                    $t->description,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
