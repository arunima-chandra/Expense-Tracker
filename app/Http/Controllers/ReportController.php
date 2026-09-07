<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Get monthly transaction report for the authenticated user.
     *
     * @param Request $request
     * @param int $year
     * @param int $month
     * @return \Illuminate\Http\JsonResponse
     */
    public function monthly(Request $request, int $year, int $month)
    {
        if ($year < 2000 || $year > 2099 || $month < 1 || $month > 12) {
            return response()->json([
                'message' => 'Invalid year or month specified. Year must be 2000–2099 and month must be 1–12.'
            ], 422);
        }

        $userId = $request->user()->id;

        $transactions = Transaction::forUser($userId)
            ->inMonth($year, $month)
            ->with('account')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalIncome = (float) $transactions->where('type', 'income')->sum('amount');
        $totalExpense = (float) $transactions->where('type', 'expense')->sum('amount');
        $netBalance = round($totalIncome - $totalExpense, 2);

        $dateObj = Carbon::createFromDate($year, $month, 1);
        $monthName = $dateObj->translatedFormat('F');

        // Grouping by category
        $categoryBreakdown = $transactions->groupBy('category')->map(function ($items, $category) {
            $catIncome = (float) $items->where('type', 'income')->sum('amount');
            $catExpense = (float) $items->where('type', 'expense')->sum('amount');

            return [
                'category' => $category ?: 'General',
                'income' => round($catIncome, 2),
                'expense' => round($catExpense, 2),
                'total' => round($items->sum('amount'), 2),
                'count' => $items->count(),
            ];
        })->values();

        Log::info('Monthly report generated', [
            'user_id' => $userId,
            'year' => $year,
            'month' => $month,
            'transactions_count' => $transactions->count(),
        ]);

        return response()->json([
            'year' => (int) $year,
            'month' => (int) $month,
            'month_name' => $monthName,
            'total_income' => round($totalIncome, 2),
            'total_expense' => round($totalExpense, 2),
            'net_balance' => $netBalance,
            'transaction_count' => $transactions->count(),
            'transactions' => $transactions,
            'category_breakdown' => $categoryBreakdown,
        ]);
    }

    /**
     * Get yearly month-by-month summary report for the authenticated user.
     *
     * @param Request $request
     * @param int $year
     * @return \Illuminate\Http\JsonResponse
     */
    public function yearly(Request $request, int $year)
    {
        if ($year < 2000 || $year > 2099) {
            return response()->json([
                'message' => 'Invalid year specified. Year must be 2000–2099.'
            ], 422);
        }

        $userId = $request->user()->id;

        $transactions = Transaction::forUser($userId)
            ->inYear($year)
            ->get();

        $monthsData = [];
        $yearlyIncome = 0;
        $yearlyExpense = 0;

        for ($m = 1; $m <= 12; $m++) {
            $monthTx = $transactions->filter(function ($t) use ($m) {
                return (int) Carbon::parse($t->date)->format('n') === $m;
            });

            $income = (float) $monthTx->where('type', 'income')->sum('amount');
            $expense = (float) $monthTx->where('type', 'expense')->sum('amount');
            $balance = round($income - $expense, 2);

            $yearlyIncome += $income;
            $yearlyExpense += $expense;

            $monthsData[] = [
                'month' => $m,
                'month_name' => Carbon::create()->month($m)->translatedFormat('F'),
                'income' => round($income, 2),
                'expense' => round($expense, 2),
                'balance' => $balance,
                'transaction_count' => $monthTx->count(),
            ];
        }

        $netBalance = round($yearlyIncome - $yearlyExpense, 2);

        Log::info('Yearly report generated', [
            'user_id' => $userId,
            'year' => $year,
            'total_income' => round($yearlyIncome, 2),
            'total_expense' => round($yearlyExpense, 2),
        ]);

        return response()->json([
            'year' => (int) $year,
            'total_income' => round($yearlyIncome, 2),
            'total_expense' => round($yearlyExpense, 2),
            'net_balance' => $netBalance,
            'months' => $monthsData,
        ]);
    }
}
