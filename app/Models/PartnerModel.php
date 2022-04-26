<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class PartnerModel extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'partner';
    protected $fillable = [

        'serviceid',
        'partnername',
        'contactnumber',
        'address',
        'servicestarttime',
        'serviceendtime',
        'district',
        'profilepic',
        'username',
        'password',
        'email',
        'nic',
        'brcopy',
        'accountstatus',
        'servicestatus',
        'description'
    ];

    public $timestamps = false;
}
