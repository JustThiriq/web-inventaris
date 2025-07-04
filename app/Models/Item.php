<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int|null $category_id
 * @property int|null $warehouse_id
 * @property string|null $barcode
 * @property int|null $min_stock
 * @property int|null $current_stock
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Category|null $category
 * @property Warehouse|null $warehouse
 * @property Collection|ItemRequest[] $item_requests
 */
class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $casts = [
        'category_id' => 'int',
        'warehouse_id' => 'int',
        'min_stock' => 'int',
        'current_stock' => 'int',
    ];

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'warehouse_id',
        'barcode',
        'min_stock',
        'current_stock',
    ];

    public function scopeActive($query)
    {
        // where category_id is not deleted
        return $query->whereHas('category', function ($q) {
            $q->whereNull('deleted_at');
        })->whereHas('warehouse', function ($q) {
            $q->whereNull('deleted_at');
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function item_requests()
    {
        return $this->hasMany(ItemRequest::class);
    }
}
