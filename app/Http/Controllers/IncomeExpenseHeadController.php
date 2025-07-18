<?php

namespace App\Http\Controllers;

use App\Models\IncomeExpenseHead;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IncomeExpenseHeadController extends Controller
{
    public function index()
    {
        $incomeExpenseHeads = IncomeExpenseHead::query()
            ->when(
                request()->type,
                fn ($q, $type) => $q->where('type', $type)
            )
            ->get();

        return response()->json($incomeExpenseHeads);
    }

    public function store(Request $request)
    {
        $incomeExpenseHead = IncomeExpenseHead::create(
            $this->validatedData($request)
        );

        return response()->json([
            'message' => 'Publisher created successfully',
            'income_expense_head' => $incomeExpenseHead
        ], 201);
    }

    public function show($id)
    {
        $incomeExpenseHead = IncomeExpenseHead::find($id);

        if (!$incomeExpenseHead) {
            return response()->json([
                'message' => 'Publisher not found'
            ], 404);
        }

        return response()->json($incomeExpenseHead);
    }

    public function update(Request $request, $id)
    {
        $incomeExpenseHead = IncomeExpenseHead::find($id);

        if (!$incomeExpenseHead) {
            return response()->json([
                'message' => 'Publisher not found'
            ], 404);
        }

        $incomeExpenseHead->update(
            $this->validatedData($request, $incomeExpenseHead->id)
        );

        return response()->json([
            'message' => 'Publisher updated successfully',
            'income_expense_head' => $incomeExpenseHead
        ]);
    }

    public function destroy($id)
    {
        $incomeExpenseHead = IncomeExpenseHead::find($id);

        if (!$incomeExpenseHead) {
            return response()->json([
                'message' => 'Publisher not found'
            ], 404);
        }

        $incomeExpenseHead->delete();

        return response()->json([
            'message' => 'Publisher deleted successfully'
        ]);
    }

    private function validatedData($request, $id = null): array
    {
        return $request->validate([
            'type' => 'required|in:Income,Expense',
            'name' => [
                'required',
                Rule::unique(IncomeExpenseHead::class, 'name')
                    ->where('type', $request->type)
                    ->ignore($id)
            ],
        ]);
    }
}
