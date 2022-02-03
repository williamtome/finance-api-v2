<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RevenueRepository;
use App\Http\Requests\ResumeRequest;
use App\Models\Revenue;
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
        $revenues = $request->has('descricao')
            ? $this->repository->getByDescription($request->descricao)
            : $this->repository->get();

        return new JsonResponse($revenues);
    }

    public function store(Request $request): void
    {
        $revenue = new Revenue();
        $revenue->create($request->all());
    }

    public function show($id): JsonResponse
    {
        $revenue = Revenue::findOrFail($id);

        return new JsonResponse($revenue);
    }

    public function update(Request $request, $id): void
    {
        $revenue = Revenue::findOrFail($id);
        $revenue->update($request->all());
    }

    public function destroy($id): void
    {
        Revenue::destroy($id);
    }

    public function listPerMonth(ResumeRequest $request): JsonResponse
    {
        $filterPatternDate = $this->filterDate(
            $request->route('month'),
            $request->route('year')
        );

        $revenues = $this->repository->getByDate($filterPatternDate);

        return new JsonResponse([
            'data' => $revenues
        ]);
    }

    private function filterDate(string $month, string $year): string
    {
        $month = $month <= 9 ? 0 . $month : $month;

        return $year . '-' . $month . '-%';
    }
}
