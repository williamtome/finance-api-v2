<?php

namespace App\Http\Repositories;

use App\Models\Revenue;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RevenueRepository
{
    public function getAll(): array
    {
        return Revenue::all();
    }

    public function create(array $attributes): void
    {
        Revenue::create($attributes);
    }

    public function show(int $id)
    {
        return Revenue::findOrFail($id);
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

    public function getByDate(string $date): Collection
    {
        return DB::table('revenues')
            ->where('date', 'like', $date)
            ->get();
    }

    public function getByDescription(string $descricao): JsonResponse
    {
        return Revenue::where('description', 'like', '%' . strtolower($descricao) . '%')->get();
    }
}
