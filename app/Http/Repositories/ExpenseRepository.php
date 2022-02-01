<?php

namespace App\Http\Repositories;

use App\Models\Expense;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExpenseRepository
{
    public function getByDate(string $date): Collection
    {
        return DB::table('expenses')
            ->where('date', 'like', $date)->get();
    }

    public function getByDescription(string $description)
    {
        return Expense::where('description', $description)->get();
    }
}
