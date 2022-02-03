<?php

namespace App\Http\Repositories;

use App\Models\Revenue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RevenueRepository
{
    public function get()
    {
        return Revenue::all();
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
