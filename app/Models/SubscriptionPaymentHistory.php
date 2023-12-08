<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class SubscriptionPaymentHistory extends Model
{
    use HasFactory;

    public $table='subscription_payment_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subscription_id',
        'plan_id',
        'payment_date',
        'payment_id',
        'payment_refrence',
        'status',
        'coupon_code',
        'plan_amount',
        'coupon_discount_amount',
        'final_pay'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'subscription_id' => 'integer',
        'plan_id' => 'integer',
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'status' => 'boolean',
    ];


    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
