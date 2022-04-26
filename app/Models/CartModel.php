<?php



namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class CartModel extends Model{
    use HasApiTokens, HasFactory;

    protected $table="ewcart";

    protected $fillable=[
        'userid',
        'listingid',
        'listingtypeid',
        'quantity',
        'totalprice',
        'status',
        'purpose'
    ];

    public $timestamps=false;

    
}




?>