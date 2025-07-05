<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukRequest extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'request_number',
        'request_date',
        'estimated_price',
        'description',
        'status',
        'admin_notes',
        'user_id',
    ];

    protected $casts = [
        'estimated_price' => 'decimal:2',
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
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope untuk status approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope untuk status rejected
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => '<span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Pending</span>',
            self::STATUS_APPROVED => '<span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Approved</span>',
            self::STATUS_REJECTED => '<span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Rejected</span>',
        ];

        return $badges[$this->status] ?? $badges[self::STATUS_PENDING];
    }

    public function details()
    {
        return $this->hasMany(ProductRequestDetail::class, 'produk_request_id');
    }
}
