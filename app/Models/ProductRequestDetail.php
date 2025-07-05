<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRequestDetail extends Model
{
    protected $fillable = [
        'produk_request_id',
        'item_id',
        'quantity',
    ];

    protected $casts = [
        'estimated_price' => 'decimal:2',
    ];

    /**
     * Relasi ke model ProdukRequest
     */
    public function produkRequest()
    {
        return $this->belongsTo(ProdukRequest::class);
    }

    /**
     * Relasi ke model Item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
