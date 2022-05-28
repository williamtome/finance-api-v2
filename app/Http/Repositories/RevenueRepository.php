<?php

namespace App\Http\Repositories;

use App\Models\Revenue;
use App\Traits\FormatterDateTrait;
use Illuminate\Support\Collection;

class RevenueRepository
{
    use FormatterDateTrait;

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

        return Revenue::where('date', 'like', $formattedDate)->get();
    }

    public function getByDescription(string $descricao): Collection
    {
        return Revenue::where('description', 'like', '%' . strtolower($descricao) . '%')->get();
    }
}
