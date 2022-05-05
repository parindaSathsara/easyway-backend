<?php



namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ServicesModel extends Model{
    use HasApiTokens, HasFactory;

    protected $table="services";

    protected $fillable=[
        'servicename',
        'servicetype',
        'servicedescription'
    ];

    public $timestamps=false;  
}




?>