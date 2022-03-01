<?php

namespace App\Http\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getLastCategory()
    {
        return Category::where('name', 'Outras')->first();
    }

    public function find(int $id)
    {
        return Category::find($id);
    }
}
