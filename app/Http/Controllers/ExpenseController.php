<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ExpenseRepository;
use App\Http\Requests\ExpenseRequest;
use App\Http\Requests\ResumeRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExpenseController extends Controller
{
    private $repository;

    public function __construct(ExpenseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request): JsonResponse
    {
        $expenses = $request->has('descricao')
            ? $this->repository->getByDescription($request->descricao)
            : $this->repository->get();

        return new JsonResponse($expenses);
    }

    public function store(ExpenseRequest $request): void
    {
        $this->mergeCategory($request);

        $revenue = new Expense();
        $revenue->create($request->all());
    }

    public function show($id): JsonResponse
    {
        $expense = Expense::findOrFail($id);

        return new JsonResponse($expense);
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

    public function listPerMonth(ResumeRequest $request): JsonResponse
    {
        $filterPatternDate = $this->filterDate(
            $request->route('month'),
            $request->route('year')
        );

        $expenses = $this->repository->getByDate($filterPatternDate);

        return new JsonResponse([
            'data' => $expenses
        ]);
    }

    private function filterDate(string $month, string $year): string
    {
        $month = $month <= 9 ? 0 . $month : $month;

        return $year . '-' . $month . '-%';
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
