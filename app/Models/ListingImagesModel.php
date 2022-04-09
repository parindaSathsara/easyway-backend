<?php



namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ListingImagesModel extends Model{
    use HasApiTokens, HasFactory;

    protected $table="listing_images";

    protected $fillable=[
        'listingid',
        'listingimageurl',

    ];


    public $timestamps=false;

    
}




?>