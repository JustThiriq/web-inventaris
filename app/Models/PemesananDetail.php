<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananDetail extends Model
{
    protected $fillable = [
        'pemesanan_id',
        'item_id',
        'jumlah',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
