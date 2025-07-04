<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'harga_estimasi',
        'deskripsi',
        'status',
        'catatan_admin',
        'user_id',
    ];

    protected $casts = [
        'harga_estimasi' => 'decimal:2',
    ];

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk status pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk status approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope untuk status rejected
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Pending</span>',
            'approved' => '<span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Approved</span>',
            'rejected' => '<span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Rejected</span>',
        ];

        return $badges[$this->status] ?? $badges['pending'];
    }
}
