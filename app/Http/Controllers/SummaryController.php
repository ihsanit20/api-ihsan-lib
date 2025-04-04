<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummaryController extends Controller
{
    public function getIncomesAndExpenses()
    {
        $startDate = request('start_date') 
            ? Carbon::parse(request('start_date')) 
            : Carbon::now()->startOfMonth();
    
        $endDate = request('end_date') 
            ? Carbon::parse(request('end_date')) 
            : Carbon::now()->endOfMonth();

        $incomes = [];
        $expenses = [];
        
        $incomes[] = [
            'name' => 'বই বিক্রয়',
            'amount' => Payment::query()
                ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                ->sum('amount'),
        ];

        $expenses[] = [
            'name' => 'বই ক্রয়',
            'amount' => Stock::query()
                ->whereBetween('stock_date', [$startDate->toDateString(), $endDate->toDateString()])
                ->select(DB::raw('SUM(quantity * production_price) as total'))
                ->value('total') ?? 0,
        ];

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
