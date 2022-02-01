<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RevenueRepository;
use App\Http\Requests\ResumeRequest;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RevenueController extends Controller
{
    private $repository;

    public function __construct(RevenueRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request): Response
    {
        $revenues = $request->has('descricao')
            ? $this->repository->getByDescription($request->descricao)
            : $this->repository->get();

        return new Response($revenues);
    }

    public function store(Request $request): void
    {
        $revenue = new Revenue();
        $revenue->create($request->all());
    }

    public function show($id): Response
    {
        $revenue = Revenue::findOrFail($id);

        return new Response($revenue);
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

    public function listPerMonth(ResumeRequest $request): Response
    {
        $filterPatternDate = $this->filterDate(
            $request->route('month'),
            $request->route('year')
        );

        $revenues = $this->repository->getByDate($filterPatternDate);

        return new Response([
            'data' => $revenues
        ]);
    }

    private function filterDate(string $month, string $year): string
    {
        $month = $month <= 9 ? 0 . $month : $month;

        return $year . '-' . $month . '-%';
    }
}
