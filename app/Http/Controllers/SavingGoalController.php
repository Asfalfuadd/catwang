<?php

namespace App\Http\Controllers;

use App\Models\SavingGoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SavingGoalController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $goals = $user->savingGoals()->orderBy('created_at', 'desc')->get();

        foreach ($goals as $goal) {
            $goal->percentage = $goal->target_amount > 0
                ? min(100, round(($goal->current_amount / $goal->target_amount) * 100))
                : 0;
            $goal->remaining = max(0, $goal->target_amount - $goal->current_amount);
        }

        $totalTarget = (float) $goals->sum('target_amount');
        $totalCollected = (float) $goals->sum('current_amount');

        return view('savings.index', compact('goals', 'totalTarget', 'totalCollected'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'target_amount' => ['required', 'numeric', 'min:1000'],
            'current_amount' => ['nullable', 'numeric', 'min:0'],
            'target_date' => ['nullable', 'date', 'after:today'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['current_amount'] = $validated['current_amount'] ?? 0;

        $user->savingGoals()->create($validated);

        return redirect()->route('savings.index')
            ->with('success', 'Target tabungan berhasil dibuat!');
    }

    public function addFunds(Request $request, SavingGoal $goal): RedirectResponse
    {
        if ($goal->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000'],
        ]);

        $goal->increment('current_amount', $validated['amount']);

        return redirect()->route('savings.index')
            ->with('success', 'Berhasil menambahkan Rp '.number_format($validated['amount'], 0, ',', '.')." ke tabungan {$goal->name}!");
    }

    public function destroy(SavingGoal $goal): RedirectResponse
    {
        if ($goal->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $goal->delete();

        return redirect()->route('savings.index')
            ->with('success', 'Target tabungan berhasil dihapus.');
    }
}
