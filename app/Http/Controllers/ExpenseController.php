<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ExpenseRepository;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExpenseController extends Controller
{
    private $repository;

    public function __construct(ExpenseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request): Response
    {
        $expenses = $request->has('descricao')
            ? $this->repository->getByDescription($request->descricao)
            : $this->repository->get();

        return new Response($expenses);
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
