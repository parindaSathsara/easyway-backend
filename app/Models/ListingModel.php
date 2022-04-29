<?php



namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ListingModel extends Model{
    use HasApiTokens, HasFactory;

    protected $table="service_listings";

    protected $fillable=[
        'listingstatus',
        'partnerid',
        'listingtitle',
        'listingpublishdate',
        'listingendingdate',
        'listingtype',
        'listingprice',
        'listingdescription'
    ];

    

    public $timestamps=false;

    
}




?>