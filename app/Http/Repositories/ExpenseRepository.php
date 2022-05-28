<?php

namespace App\Http\Repositories;

use App\Models\Category;
use App\Models\Expense;
use App\Traits\FormatterDateTrait;
use Illuminate\Support\Collection;

class ExpenseRepository
{
    use FormatterDateTrait;

    public function getAll()
    {
        return Expense::with('category')->get();
    }

    public function create(array $attributes): void
    {
        $revenue = new Expense();
        $revenue->create($attributes);
    }

    public function find(int $id): Expense
    {
        $expense = Expense::findOrFail($id);

        return $expense->with('category')->first();
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

    public function getByDate(string $year, string $month): Collection
    {
        $formattedDate = $this->formatDate($year, $month);

        return Expense::with('category')
            ->where('expenses.date', 'like', $formattedDate)
            ->get();
    }

    public function getByDateAndCategory(string $year, string $month): array
    {
        $formattedDate = $this->formatDate($year, $month);

        $totalExpensesByCategories = Category::with(['expenses' => function ($query) use ($formattedDate) {
            return $query->where('date', 'like', $formattedDate);
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
        return Expense::where('description', 'like', '%' . strtolower($description) . '%')->get();
    }
}
