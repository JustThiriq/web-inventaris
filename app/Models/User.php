<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password_hash
 * @property string $role
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 * @property Collection|ItemRequest[] $item_requests
 */
class User extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'users';

    protected $casts = [
        'is_active' => 'bool',
        'last_login' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'last_login',
        'phone',
    ];

    public function item_requests()
    {
        return $this->hasMany(ItemRequest::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
