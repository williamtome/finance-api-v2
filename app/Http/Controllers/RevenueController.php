<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RevenueRepository;
use App\Http\Requests\ResumeRequest;
use App\Http\Requests\RevenueRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    private $repository;

    public function __construct(RevenueRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request): JsonResponse
    {
        return $request->filled('descricao')
            ? $this->repository->getByDescription($request->descricao)
            : $this->repository->getAll();

    }

    public function store(RevenueRequest $request): void
    {
        $this->repository->create($request->all());
    }

    public function show($id): JsonResponse
    {
        return $this->repository->show($id);
    }

    public function update(Request $request, int $id): void
    {
        $this->repository->update($request->all(), $id);
    }

    public function destroy(int $id): void
    {
        $this->repository->delete($id);
    }

    public function listPerMonth(ResumeRequest $request): JsonResponse
    {
        $filterPatternDate = $this->filterDate(
            $request->route('month'),
            $request->route('year')
        );

        return $this->repository->getByDate($filterPatternDate);
    }

    private function filterDate(string $month, string $year): string
    {
        $month = $month <= 9 ? 0 . $month : $month;

        return $year . '-' . $month . '-%';
    }
}
