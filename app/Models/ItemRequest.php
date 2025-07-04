<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemRequest
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $item_id
 * @property int|null $quantity_requested
 * @property string|null $status
 * @property int|null $approved_by
 * @property Carbon|null $request_date
 * @property string|null $notes
 * @property string|null $barcode
 * @property User|null $user
 * @property Item|null $item
 */
class ItemRequest extends Model
{
    protected $table = 'item_requests';

    public $timestamps = false;

    protected $casts = [
        'user_id' => 'int',
        'item_id' => 'int',
        'quantity_requested' => 'int',
        'approved_by' => 'int',
        'request_date' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'item_id',
        'quantity_requested',
        'status',
        'approved_by',
        'request_date',
        'notes',
        'barcode',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
