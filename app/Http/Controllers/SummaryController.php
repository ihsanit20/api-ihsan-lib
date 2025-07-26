<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Payment;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    const CACHE_DURATION_IN_MINUTE = 1;

    public function getIncomesAndExpenses()
    {
        $startDate = Carbon::parse(request('start_date', now()->startOfMonth()));
        $endDate = Carbon::parse(request('end_date', now()->endOfMonth()));

        $start = $startDate->startOfDay();
        $end = $endDate->endOfDay();

        $incomes = [];

        $incomes[] = [
            'name' => 'অর্ডার পেমেন্ট',
            'amount' => Payment::whereBetween('created_at', [$start, $end])->sum('amount'),
        ];

        $monthlyIncomes = Income::query()
            ->selectRaw('income_expense_heads.name as name, SUM(amount) as amount')
            ->join('income_expense_heads', 'incomes.income_expense_head_id', '=', 'income_expense_heads.id')
            ->whereBetween('date', [$start, $end])
            ->groupBy('income_expense_heads.name')
            ->get()
            ->toArray();

        $incomes = array_merge($incomes, $monthlyIncomes);

        $expenses = [];

        // $bookPurchase = Stock::whereBetween('stock_date', [$startDate->toDateString(), $endDate->toDateString()])
        //     ->selectRaw('SUM(quantity * production_price) as total')
        //     ->value('total') ?? 0;

        // $expenses[] = [
        //     'name' => 'বই ক্রয়',
        //     'amount' => $bookPurchase,
        // ];

        $monthlyExpenses = Expense::query()
            ->selectRaw('income_expense_heads.name as name, SUM(amount) as amount')
            ->join('income_expense_heads', 'expenses.income_expense_head_id', '=', 'income_expense_heads.id')
            ->whereBetween('date', [$start, $end])
            ->groupBy('income_expense_heads.name')
            ->get()
            ->toArray();

        $expenses = array_merge($expenses, $monthlyExpenses);

        $incomes = array_map(function ($income) {
            $income['amount'] = (int) round($income['amount']);
            return $income;
        }, $incomes);

        $expenses = array_map(function ($expense) {
            $expense['amount'] = (int) round($expense['amount']);
            return $expense;
        }, $expenses);

        $totalIncomes = array_sum(array_column($incomes, 'amount'));
        $totalExpenses = array_sum(array_column($expenses, 'amount'));

        return response()->json([
            'incomes' => $incomes,
            'expenses' => $expenses,
            'total_incomes' => $totalIncomes,
            'total_expenses' => $totalExpenses,
            'profit' => $totalIncomes - $totalExpenses,
        ]);
    }
}
