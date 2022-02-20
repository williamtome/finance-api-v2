<?php

namespace App\Http\Repositories;

use App\Models\Revenue;
use App\Traits\ApiResponser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RevenueRepository
{
    use ApiResponser;

    public function getAll()
    {
        return Revenue::all();
    }

    public function create(array $attributes)
    {
        Revenue::create($attributes);
    }

    public function show(int $id)
    {
        $revenue = Revenue::findOrFail($id);

        return $this->success($revenue);
    }

    public function update(array $attributes, int $id)
    {
        $revenue = Revenue::findOrFail($id);

        $revenue->update($attributes);
    }

    public function delete(int $id)
    {
        Revenue::destroy($id);
    }

    public function getByDate(string $date): Collection
    {
        return DB::table('revenues')
            ->where('date', 'like', $date)->get();
    }

    public function getByDescription(string $descricao)
    {
        return Revenue::where('description', $descricao)->get();
    }
}
