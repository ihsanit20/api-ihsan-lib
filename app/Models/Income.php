<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function incomeHead()
    {
        return $this->belongsTo(IncomeExpenseHead::class, 'income_expense_head_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
