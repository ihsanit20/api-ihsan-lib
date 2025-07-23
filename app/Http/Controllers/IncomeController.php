<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Income::query()
            ->with('incomeHead:id,type,name')
            ->get();

        return response()->json($incomes);
    }

    public function store(Request $request)
    {
        $income = Income::create(
            $this->validatedData($request) + [
                'user_id' => $request->user()->id,
            ]
        );

        return response()->json([
            'message' => 'Publisher created successfully',
            'income_expense_head' => $income
        ], 201);
    }

    public function show($id)
    {
        $income = Income::find($id);

        if (!$income) {
            return response()->json([
                'message' => 'Publisher not found'
            ], 404);
        }

        return response()->json($income);
    }

    public function update(Request $request, $id)
    {
        $income = Income::find($id);

        if (!$income) {
            return response()->json([
                'message' => 'Publisher not found'
            ], 404);
        }

        $income->update(
            $this->validatedData($request, $income->id)
        );

        return response()->json([
            'message' => 'Publisher updated successfully',
            'income_expense_head' => $income
        ]);
    }

    public function destroy($id)
    {
        $income = Income::find($id);

        if (!$income) {
            return response()->json([
                'message' => 'Publisher not found'
            ], 404);
        }

        $income->delete();

        return response()->json([
            'message' => 'Publisher deleted successfully'
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
