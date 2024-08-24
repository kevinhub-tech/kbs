<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class address extends Model
{
    use HasFactory, HasUuids;


     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_addresses';


    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'address_id';

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
        'user_id',
        'address',
        'state',
        'phone_number',
        'postal_code',
        'default_address',
        'default_billing_address',
        'created_at',
        'updated_at'
    ];
}
