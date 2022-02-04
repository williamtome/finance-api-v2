<?php

namespace App\Http\Repositories;

use App\Models\Expense;
use Illuminate\Support\Collection;

class ExpenseRepository
{
    public function get()
    {
        return Expense::all();
    }

    public function getByDate(string $date): Collection
    {
        $expenses = Expense::with('category')
            ->where('expenses.date', 'like', $date)
            ->get();

        return $expenses->each(function ($expense) {
            return [
                'id' => $expense->id,
                'description' => $expense->description,
                'amount' => (float) $expense->amount,
                'date' => $expense->date,
                'category' => $expense->category->name,
            ];
        });
    }

    public function getByDescription(string $description)
    {
        return Expense::where('description', $description)->get();
    }
}
