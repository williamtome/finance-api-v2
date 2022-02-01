<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ExpenseRepository;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExpenseController extends Controller
{
    private $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function index(Request $request): Response
    {
        if ($request->has('descricao')) {
            $revenues = $this->expenseRepository->getByDescription($request->descricao);
            return new Response($revenues);
        }

        return new Response(Expense::all());
    }

    public function store(Request $request): void
    {
        $this->mergeCategory($request);

        $revenue = new Expense();
        $revenue->create($request->all());
    }

    public function show($id): Response
    {
        $expense = Expense::findOrFail($id);

        return new Response($expense);
    }

    public function update(Request $request, $id): void
    {
        $expense = Expense::findOrFail($id);
        $expense->update($request->all());
    }

    public function destroy($id): void
    {
        Expense::destroy($id);
    }

    private function mergeCategory(Request $request): void
    {
        if ($request->has('category')) {
            $request->merge(['category_id' => $request->category]);
        } else {
            $request->merge(['category_id' => 8]);
        }
    }
}
