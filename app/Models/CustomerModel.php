<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class CustomerModel extends Model
{
    use HasApiTokens, HasFactory;
    protected $table = 'customer';
    protected $fillable = [

        'customerid',
        'customername',
        'customeremail',
        'profilepic',
        'customerdistrict',
        'customerpassword',
        'customerusername',
        'customercontact',
        'customerhomeaddress',
        'joineddate'

    ];

    public $timestamps = false;
}
