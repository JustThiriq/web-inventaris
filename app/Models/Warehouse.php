<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'manager_name',
        'phone',
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