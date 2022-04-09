<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class AdminModel extends Model
{
    use HasApiTokens,HasFactory;
    protected $table='administrator';
    protected $fillable=[
        'username',
        'email',
        'nic',
        'contact',
        'password',
        'district',
        'role',
    ];

    public $timestamps=false;
}
