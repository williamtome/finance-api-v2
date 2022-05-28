<?php

namespace App\Http\Repositories;

use App\Models\Revenue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RevenueRepository
{
    public function getAll()
    {
        return Revenue::all();
    }

    public function create(array $attributes): void
    {
        Revenue::create($attributes);
    }

    public function find(int $id): Revenue
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

    public function getByDate(string $year, string $month): Collection
    {
        $formattedDate = $this->formatDate($year, $month);

        return DB::table('revenues')
            ->where('date', 'like', $formattedDate)
            ->get();
    }

    public function getByDescription(string $descricao): Collection
    {
        return Revenue::where('description', 'like', '%' . strtolower($descricao) . '%')->get();
    }

    private function formatDate(string $year, string $month): string
    {
        $month = $month <= 9 ? 0 . $month : $month;

        return $year . '-' . $month . '-%';
    }
}
