<?php

namespace App\Http\Repositories;

use App\Models\Revenue;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RevenueRepository
{
    use ApiResponser;

    public function getAll(): JsonResponse
    {
        return $this->success(Revenue::all());
    }

    public function create(array $attributes): void
    {
        Revenue::create($attributes);
    }

    public function show(int $id)
    {
        $revenue = Revenue::findOrFail($id);

        return $this->success($revenue);
    }

    public function update(array $attributes, int $id): void
    {
        $revenue = Revenue::findOrFail($id);

        $revenue->update($attributes);
    }

    public function delete(int $id): void
    {
        Revenue::destroy($id);
    }

    public function getByDate(string $date): JsonResponse
    {
        $revenues = DB::table('revenues')
            ->where('date', 'like', $date)
            ->get();

        return $this->success($revenues);
    }

    public function getByDescription(string $descricao): JsonResponse
    {
        $revenues = Revenue::where('description', 'like', '%' . strtolower($descricao) . '%')->get();

        return $revenues
            ? $this->success($revenues)
            : $this->error(400, 'Erro ao listar as receitas!');
    }
}
