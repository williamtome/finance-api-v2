<?php

namespace App\Http\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExpenseRepository
{
    public function getByDate(string $date): Collection
    {
        return DB::table('expenses')
            ->where('date', 'like', $date)->get();
    }
}
