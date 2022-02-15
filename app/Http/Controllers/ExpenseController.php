<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ExpenseRepository;
use App\Http\Requests\ExpenseRequest;
use App\Http\Requests\ResumeRequest;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExpenseController extends Controller
{
    use ApiResponser;

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

        return $this->success($expenses);
    }

    public function store(ExpenseRequest $request): void
    {
        $this->mergeCategory($request);

        $this->repository->create($request->all());
    }

    public function show(int $id): JsonResponse
    {
        $expense = $this->repository->find($id);

        return $this->success($expense);
    }

    public function update(Request $request, $id): void
    {
        $this->repository->update($request->all(), $id);
    }

    public function destroy($id): void
    {
        $this->repository->delete($id);
    }

    public function listPerMonth(ResumeRequest $request): JsonResponse
    {
        $filterPatternDate = $this->filterDate(
            $request->route('month'),
            $request->route('year')
        );

        $expenses = $this->repository->getByDate($filterPatternDate);

        return $this->success([
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
        $request->merge([
            'category_id' => $request->has('category')
                ? $request->category 
                : 8,
        ]);
    }
}
