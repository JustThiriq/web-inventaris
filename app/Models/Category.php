<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
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
}