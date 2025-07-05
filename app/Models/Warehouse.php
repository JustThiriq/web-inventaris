<?php

namespace App\Models;

use App\Models\Core\WithSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes, WithSearch;

    protected $searchable = [
        'name',
        'location',
        'manager_name',
        'phone',
    ];

    protected $fillable = [
        'name',
        'location',
        'manager_name',
        'phone',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // Scopes
    public function scopeWithItemCount($query)
    {
        return $query->withCount('items');
    }

    // Accessors
    public function getItemCountAttribute()
    {
        return $this->items()->count();
    }

    public function getTotalStockAttribute()
    {
        return $this->items()->sum('current_stock');
    }
}
