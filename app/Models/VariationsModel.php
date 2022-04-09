<?php



namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class VariationsModel extends Model{
    use HasApiTokens, HasFactory;

    protected $table="listing_variations";

    protected $fillable=[
        'listingid',
        'variationtitle',
        'variationname',
        'variationprice',
    ];


    public $timestamps=false;

    
}




?>