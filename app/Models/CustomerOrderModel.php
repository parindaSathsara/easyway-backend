<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class CustomerOrderModel extends Model
{
    use HasApiTokens,HasFactory;
    protected $table='orders';
    protected $fillable=[
        'customerid',
        'listingid',
        'orderstatus',
        'remark',
        'orderdate',
        'ordertime',
        'fullname',
        'contactnumber',
        'address',
        'district',
        'city',
        'paymentoption',
        'listingtypeid',
        'quantity',
        'totalprice',
    ];

    public $timestamps=false;
}
