<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class Plan extends Model
{
    use HasFactory;

    protected $table='plans';


    // const STRIP_DETAIL_FROM_STRIP=[
    //     'price_1JWFJKBbrKa9p7qIlUBj1XFs'=>'Monthly',
    //     'price_1JWFL5BbrKa9p7qIXdnIRRrA'=>'Annual'
    // ];

    // const PLAN_AMOUNT_FROM_STRIPE=[
    //         'price_1JWFJKBbrKa9p7qIlUBj1XFs'=>10,
    //         'price_1JWFL5BbrKa9p7qIXdnIRRrA'=>99
    // ];

    // const STRIP_All_PLAN=[
    //         [
    //             'plan_id'=>'price_1JWFJKBbrKa9p7qIlUBj1XFs',
    //             'plan_name'=>'Monthly',
    //             'plan_amount'=>'10'
    //         ],
    //         [
    //             'plan_id'=>'price_1JWFL5BbrKa9p7qIXdnIRRrA',
    //             'plan_name'=>'Annual',
    //             'plan_amount'=>'99'
    //         ]
       
    // ];


    // const STRIP_All_PLAN_WITH_PRE_DEFINED_KEY=[
    //         [
    //             'monthly'=>[
    //                     'plan_id'=>'price_1JWFJKBbrKa9p7qIlUBj1XFs',
    //                     'plan_name'=>'Monthly',
    //                     'plan_amount'=>'10'
    //                 ],
    //             'annual'=>[
    //                     'plan_id'=>'price_1JWFL5BbrKa9p7qIXdnIRRrA',
    //                     'plan_name'=>'Annual',
    //                     'plan_amount'=>'99'
    //                 ]
    //     ]
    // ];
    



    const STRIP_DETAIL_FROM_STRIP=[
        'price_1JWl7kBbrKa9p7qIHcprFCPB'=>'Monthly',
        'price_1JWl7bBbrKa9p7qI2uJRR4nN'=>'Annual',
        'price_1MCkXBBbrKa9p7qIxRO1PLqm'=>'Monthly',
        'price_1MCkXBBbrKa9p7qIQF3mFjiD'=>'Annual',
        'price_1N431cBbrKa9p7qI3RjBAyyv'=>'Event',
    ];

    const PLAN_AMOUNT_FROM_STRIPE=[
        'price_1JWl7kBbrKa9p7qIHcprFCPB'=>10,
        'price_1JWl7bBbrKa9p7qI2uJRR4nN'=>99,
        'price_1M5UXCSDZ5RXkq65XnMMmXiZ'=>10,
        'price_1M5UYqSDZ5RXkq65WkwaFaGo'=>99,
        'price_1MCkXBBbrKa9p7qIxRO1PLqm'=>200,
        'price_1MCkXBBbrKa9p7qIQF3mFjiD'=>2000,
        'price_1N431cBbrKa9p7qI3RjBAyyv'=>25
    ];

    const STRIP_All_PLAN=[
            [
                'plan_id'=>'price_1JWl7kBbrKa9p7qIHcprFCPB',
                'plan_name'=>'Monthly',
                'plan_amount'=>'10'
            ],
            [
                'plan_id'=>'price_1JWl7bBbrKa9p7qI2uJRR4nN',
                'plan_name'=>'Annual',
                'plan_amount'=>'99'
            ]
       
    ];


    const STRIP_All_PLAN_WITH_PRE_DEFINED_KEY=[
            [
                'monthly'=>[
                        'plan_id'=>'price_1JWl7kBbrKa9p7qIHcprFCPB',
                        'plan_name'=>'Monthly',
                        'plan_amount'=>'10'
                    ],
                'annual'=>[
                        'plan_id'=>'price_1JWl7bBbrKa9p7qI2uJRR4nN',
                        'plan_name'=>'Annual',
                        'plan_amount'=>'99'
                    ]
        ]
    ];

    const STRIP_COMPANY_PLAN_WITH_PRE_DEFINED_KEY=[
        [
            'monthly'=>[
                    'plan_id'=>'price_1MCkXBBbrKa9p7qIxRO1PLqm',
                    'plan_name'=>'Monthly',
                    'plan_amount'=>'200'
                ],
            'annual'=>[
                    'plan_id'=>'price_1MCkXBBbrKa9p7qIQF3mFjiD',
                    'plan_name'=>'Annual',
                    'plan_amount'=>'2000'
                ]
        ]
    ];

    // Test: price_1MyaYfSDZ5RXkq65Njs51ucX
    // Live: price_1N431cBbrKa9p7qI3RjBAyyv
    const EVENT_PLAN_DETAIL_FROM_STRIPE=[
        'price_1N431cBbrKa9p7qI3RjBAyyv'=>'Submit Event Fee',
    ];
    const EVENT_PLAN_PRICE=[
        'price_1N431cBbrKa9p7qI3RjBAyyv'=>25
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_name',
        'plan_amount',
        'short_description',
        'description',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'plan_amount' => 'decimal:2',
        'status' => 'boolean',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
