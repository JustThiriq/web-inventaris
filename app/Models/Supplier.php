<?php

namespace App\Models;

use App\Models\Core\WithSearch;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use WithSearch, \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $searchable = [
        'name',
        'npwp',
        'phone',
        'address',
    ];

    protected $fillable = [
        'npwp',
        'name',
        'phone',
        'address',
    ];

}
