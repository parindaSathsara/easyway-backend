<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class DeliveryJobModel extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'deliveryjob';
    protected $fillable = [

        'jobid',
        'orderid',
        'riderid',
        'status',
        'estimatetime',
        'estimatedate',
        'totaldistance',
        'totalprice',
        'totalPayable'

    ];

    public $timestamps = false;
}
