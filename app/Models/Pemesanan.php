<?php

namespace App\Models;

use App\Models\Core\WithSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemesanan extends Model
{
    use WithSearch, SoftDeletes;

    protected $searchable = [
        'no_po',
        'no_wo',
        'tanggal_pemesanan',
        'tanggal_kedatangan',
        'tanggal_dipakai',
        'supplier.name',
        'user.name',
        'bidang.name',
    ];

    protected $fillable = [
        'product_request_id',
        'no_po',
        'no_wo',
        'tanggal_pemesanan',
        'tanggal_kedatangan',
        'tanggal_dipakai',
        'supplier_id',
        'user_id',
        'bidang_id',
        'status',
        'keterangan',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'datetime',
        'tanggal_kedatangan' => 'datetime',
        'tanggal_dipakai' => 'datetime',
        'status' => 'string',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending')->orWhere('status', 'draft');
    }

    public function scopeNotPending($query)
    {
        return $query->where('status', '!=', 'pending')->where('status', '!=', 'draft');
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function produkRequest()
    {
        return $this->belongsTo(ProdukRequest::class, 'product_request_id');
    }

    public function details()
    {
        return $this->hasMany(PemesananDetail::class);
    }

    public function getStatusLabelAttribute()
    {
        $lists = [
            'draft' => 'Draft',
            'pending' => 'Pending',
            'belum_diambil' => 'Belum diambil',
            'sudah_diambil' => 'Sudah diambil'
        ];

        return isset($lists[$this->status]) ? $lists[$this->status] : 'Tidak diketahui';
    }
    public function getStatusClassAttribute()
    {
        $lists = [
            'draft' => 'badge-danger',
            'pending' => 'badge-warning',
            'belum_diambil' => 'badge-info',
            'sudah_diambil' => 'badge-primary'
        ];

        return isset($lists[$this->status]) ? $lists[$this->status] : 'badge-secondary';
    }
}
