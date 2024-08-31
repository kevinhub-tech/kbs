<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class orders extends Model
{
    use HasFactory, HasUuids;


    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'order_id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_number',
        'payment_method',
        'refund_state',
        'is_cancelled',
        'total',
        'address_id',
        'billing_address_id',
        'vendor_id',
        'ordered_by',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'created_at',
        'updated_at'
    ];

    public function delivery_address(): HasOne
    {
        return $this->hasOne(address::class, 'address_id', 'address_id');
    }

    public function billing_address(): HasOne
    {
        return $this->hasOne(address::class, 'address_id', 'billing_address_id');
    }

    public function order_user(): HasOne
    {
        return $this->hasOne(users::class, 'user_id', 'ordered_by');
    }
}
