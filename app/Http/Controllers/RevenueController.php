<?php

namespace App\Http\Controllers;

use App\Http\Repositories\RevenueRepository;
use App\Http\Requests\ResumeRequest;
use App\Http\Requests\RevenueRequest;
use App\Http\Resources\RevenueResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RevenueController extends Controller
{
    private RevenueRepository $repository;

    public function __construct(RevenueRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $revenues = $request->has('descricao')
            ? $this->repository->getByDescription($request->descricao)
            : $this->repository->getAll();

        return RevenueResource::collection($revenues);
    }

    public function store(RevenueRequest $request): void
    {
        $this->repository->create($request->all());
    }

    public function show($id): RevenueResource
    {
        $revenue = $this->repository->find($id);

        return RevenueResource::make($revenue);
    }

    public function update(Request $request, int $id): void
    {
        $this->repository->update($request->all(), $id);
    }

    public function destroy(int $id): void
    {
        $this->repository->delete($id);
    }

    public function listPerMonth(ResumeRequest $request): AnonymousResourceCollection
    {
        $month = $request->route('month');
        $year = $request->route('year');

        $revenues = $this->repository->getByDate($year, $month);

        return RevenueResource::collection($revenues);
    }
}
