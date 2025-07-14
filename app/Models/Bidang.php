<?php

namespace App\Models;

use App\Models\Core\WithSearch;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use WithSearch;

    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
