<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    public $table = 'expenses';

    public $timestamps = false;

    protected $fillable = [
        'description',
        'amount',
        'date',
    ];
}
