<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class RiderModel extends Model
{
    use HasApiTokens,HasFactory;
    protected $table='deliveryrider';
    protected $fillable=[
        'ridername',
        'ridervehicleno',
        'ridercontact',
        'riderdistrict',
        'ridernic',
        'rideremail',
        'profilepic',
        'riderlicense',
        'description',
        'riderusername',
        'riderpassword',
        'accountstatus',
        'riderstatus'
    ];

    public $timestamps=false;
}
