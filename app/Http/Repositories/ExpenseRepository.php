<?php

namespace App\Http\Repositories;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Support\Collection;

class ExpenseRepository
{
    public function get()
    {
        return Expense::all();
    }

    public function create(array $attributes): void
    {        
        $revenue = new Expense();
        $revenue->create($attributes);
    }

    public function find(int $id): Expense
    {
        return Expense::findOrFail($id);
    }

    public function update(array $attributes, int $id): void
    {
        $expense = Expense::findOrFail($id);
        
        $expense->update($attributes);
    }

    public function delete(int $id): void
    {
        Expense::destroy($id);
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

    public function getByDateAndCategory(string $date): array
    {
        $totalExpensesByCategories = Category::with(['expenses' => function ($query) use ($date) {
            return $query->where('date', 'like', $date);
        }])->get();

        return $totalExpensesByCategories->map(function ($category) {
            return [
                'category' => $category->name,
                'total' => $category->expenses->pluck('amount')->sum(),
            ];
        })->where('total', '>', 0)
            ->toArray();
    }

    public function getByDescription(string $description)
    {
        return Expense::where('description', $description)->get();
    }
}
