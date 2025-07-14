<?php

namespace App\Models;

use App\Models\Core\WithSearch;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use WithSearch;

    protected $searchable = ['name'];
    protected $fillable = ['name'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
