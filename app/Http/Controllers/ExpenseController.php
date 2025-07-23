<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::query()
            ->with('expenseHead:id,type,name')
            ->get();

        return response()->json($expenses);
    }

    public function store(Request $request)
    {
        $expense = Expense::create(
            $this->validatedData($request) + [
                'user_id' => $request->user()->id,
            ]
        );

        return response()->json([
            'message' => 'Expense created successfully',
            'expense' => $expense
        ], 201);
    }

    public function show($id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json([
                'message' => 'Expense not found'
            ], 404);
        }

        return response()->json($expense);
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json([
                'message' => 'Expense not found'
            ], 404);
        }

        $expense->update(
            $this->validatedData($request, $expense->id)
        );

        return response()->json([
            'message' => 'Expense updated successfully',
            'expense' => $expense
        ]);
    }

    public function destroy($id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json([
                'message' => 'Expense not found'
            ], 404);
        }

        $expense->delete();

        return response()->json([
            'message' => 'Expense deleted successfully'
        ]);
    }

    private function validatedData($request, $id = null): array
    {
        return $request->validate([
            'date' => 'required',
            'income_expense_head_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]);
    }
}
