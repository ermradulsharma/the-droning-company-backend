<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;


    public $table = 'coupons';

    const STATUS_SELECT = [
        '1' => 'Active',
        '0' => 'Inactive',
    ];

    const COUPON_TYPE = [
        '1' => 'percentage',
        '2' => 'fixed',
    ];


    const VALIDATE_COUPON_CODE = [
        'fixed1dollar' => 'promo_1JTP6yBbrKa9p7qInqn5TIcO',
    ];
  

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coupon_name',
        'coupon_type',
        'start_date',
        'end_date',
        'status',
        'discount',
        'coupon_code',
        'stripe_coupon_id',
        'stripe_promotion_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
    ];


    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
