<?php

namespace App\Models;

use App\Models\Core\WithSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ProdukRequest extends Model
{
    use HasFactory, SoftDeletes, WithSearch;

    const STATUS_PENDING = 'pending';

    const STATUS_APPROVED = 'approved';

    const STATUS_REJECTED = 'rejected';

    protected $searchable = [
        'request_number',
        'description',
    ];

    protected $fillable = [
        'request_number',
        'request_date',
        'description',
        'status',
        'user_id',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'status' => 'string',
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
     * Scope untuk bukan pending
     */
    public function scopeNotPending($query)
    {
        return $query->where('status', '!=', self::STATUS_PENDING);
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

    public function pemesanan()
    {
        return $this->hasOne(Pemesanan::class, 'product_request_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(ProductRequestDetail::class, 'produk_request_id');
    }

    public function randomDigits($length = 8)
    {
        return str_pad(random_int(0, 99999999), $length, '0', STR_PAD_LEFT);
    }

    public function buatPemesanan(array $items)
    {
        // Assuming you have a Pemesanan model to handle the non-consumable items
        $pemesanan = new Pemesanan();

        $pemesanan->product_request_id = $this->id;
        $pemesanan->no_po = 'PO-' . strtoupper($this->randomDigits(8));
        $pemesanan->tanggal_pemesanan = now();
        $pemesanan->supplier_id = $this->supplier_id; // Assuming you have a supplier_id in the model
        $pemesanan->user_id = Auth::id();
        $pemesanan->bidang_id = $this->bidang_id; // Assuming you have a bidang_id in the model
        $pemesanan->status = 'draft';
        $pemesanan->keterangan = 'Pemesanan untuk item non-konsumsi';

        // Save the pemesanan
        $pemesanan->save();

        // Attach items to the pemesanan
        foreach ($items as $item) {
            $pemesanan->details()->create([
                'item_id' => $item['item_id'],
                'jumlah' => $item['quantity'],
            ]);
        }

        return $pemesanan;
    }
}
