<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Core\WithSearch;
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
    use HasFactory, WithSearch;

    protected $searchable = [
        'code',
        'name',
        'barcode',
        'category.name',
        'warehouse.name',
    ];

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
        'unit_id',
    ];

    private function randomDigits($length = 9)
    {
        $digits = '';
        for ($i = 0; $i < $length; $i++) {
            $digits .= rand(0, 9);
        }

        return $digits;
    }

    protected static function booted()
    {
        static::creating(function ($item) {
            $code = strtoupper($item->randomDigits(15));

            while (self::where('barcode', $code)->exists()) {
                $code = strtoupper($item->randomDigits(15));
            }

            $item->barcode = $code;
        });
    }

    public function scopeActive($query)
    {
        // where category_id is not deleted
        return $query->whereHas('category', function ($q) {
            $q->whereNull('deleted_at');
        })->whereHas('warehouse', function ($q) {
            $q->whereNull('deleted_at');
        });
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // public function item_requests()
    // {
    //     return $this->hasMany(ItemRequest::class);
    // }

    public function getCurrentStokAttribute()
    {
        return $this->current_stock ?? 0;
    }

    public function getMinStokAttribute()
    {
        return $this->min_stock ?? 0;
    }

    public function getBadgeLevelAttribute()
    {
        if ($this->currentStok <= $this->minStok) {
            return 'badge-danger';
        } elseif ($this->currentStok <= $this->minStok * 2) {
            return 'badge-warning';
        } else {
            return 'badge-success';
        }
    }

    public function getBadgeLabelAttribute()
    {
        if ($this->currentStok <= $this->minStok) {
            return 'Stok Rendah';
        } elseif ($this->currentStok <= $this->minStok * 2) {
            return 'Stok Cukup';
        } else {
            return 'Stok Aman';
        }
    }

    public function getBarcodeUrlAttribute()
    {
        // Check folder existence
        $barcodeDir = public_path('barcodes');
        if (! file_exists($barcodeDir)) {
            mkdir($barcodeDir, 0755, true);
        }

        // Check if barcode file exists
        $barcodePath = public_path('barcodes/'.$this->barcode.'.png');
        if (file_exists($barcodePath)) {
            return asset('barcodes/'.$this->barcode.'.png');
        }

        // Generate barcode if it doesn't exist
        $barcodeGenerator = new \Picqer\Barcode\BarcodeGeneratorPNG;
        $barcodeImage = $barcodeGenerator->getBarcode($this->barcode, $barcodeGenerator::TYPE_CODE_128);
        file_put_contents($barcodePath, $barcodeImage);

        return asset('barcodes/'.$this->barcode.'.png');
    }

    public function decrementStock($amount)
    {
        // Ensure current_stock is not null
        if ($this->current_stock === null) {
            $this->current_stock = 0;
        }

        // Decrement the stock
        $this->current_stock -= $amount;

        // Save the changes
        return $this->save();
    }
}
