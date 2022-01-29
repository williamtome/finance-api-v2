<?php

namespace App\Http\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RevenueRepository
{
    public function getByDate(string $date): Collection
    {
        return DB::table('revenues')
            ->where('date', 'like', $date)->get();
    }
}
