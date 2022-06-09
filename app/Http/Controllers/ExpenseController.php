<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\ExpenseRepository;
use App\Http\Requests\ExpenseRequest;
use App\Http\Requests\ResumeRequest;
use App\Http\Resources\ExpenseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExpenseController extends Controller
{
    private ExpenseRepository $repository;
    private CategoryRepository $categoryRepository;

    public function __construct(
        ExpenseRepository $repository,
        CategoryRepository $categoryRepository
    ) {
        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $expenses = $request->has('descricao')
            ? $this->repository->getByDescription($request->descricao)
            : $this->repository->getAll();

        return ExpenseResource::collection($expenses);
    }

    public function store(ExpenseRequest $request): void
    {
        $this->mergeCategory($request);

        $this->repository->create($request->all());
    }

    public function show(int $id): ExpenseResource
    {
        $expense = $this->repository->find($id);

        return ExpenseResource::make($expense);
    }

    public function update(ExpenseRequest $request, $id): void
    {
        $this->repository->update($request->all(), $id);
    }

    public function destroy($id): void
    {
        $this->repository->delete($id);
    }

    /**
     * @throws \Exception
     */
    public function listPerMonth(ResumeRequest $request): AnonymousResourceCollection
    {
        $year = $request->route('year');
        $month = $request->route('month');

        $expenses = $this->repository->getByDate($year, $month);

        return ExpenseResource::collection($expenses);
    }

    private function mergeCategory(Request $request): void
    {
        $otherCategory = $this->categoryRepository->getLastCategory();

        $request->merge([
            'category_id' => $request->filled('category')
                ? $request->category
                : $otherCategory->id,
        ]);
    }
}
