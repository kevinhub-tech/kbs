<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class books extends Model
{
    use HasFactory, HasUuids;


    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'book_id';

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
        'book_name',
        'book_desc',
        'author_name',
        'image',
        'stock',
        'price',
        'delivery_fee',
        'discount_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function discount() :HasOne
    {
        return $this->hasOne(discounts::class, 'discount_id', 'discount_id');
    }
}
