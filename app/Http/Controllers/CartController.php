<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminModel;
use App\Models\CartModel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class CartController extends Controller
{
    public function addToCart(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'userid' => 'required',
            'listingid' => 'required',
            'listingtypeid' => 'required',
            'quantity' => 'required',
            'totalprice' => 'required',
            'status' => 'required',
        ]);

        $token = $request->session()->token();
        $token = csrf_token();

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'validator_errors' => $validator->errors(),
            ]);
        } else {
            $cart = CartModel::create([
                'userid' => $request->userid,
                'listingid' => $request->listingid,
                'listingtypeid' => $request->listingtypeid,
                'quantity' => $request->quantity,
                'totalprice' => $request->totalprice,
                'status' => $request->status,
                'purpose' => $request->purpose,
            ]);

            $token = $cart->createToken($cart->id . '_Token')->plainTextToken;

            return response()->json(
                [
                    'status' => 200,
                    'token' => $token,
                    'cartid' => $cart->id,
                    'message' => 'Cart Added Successfully',
                ]
            );
        }
    }

    public function getCart($id)
    {
        $listingsFixed = DB::table('ewcart')
            ->join('service_listings', 'ewcart.listingid', '=', 'service_listings.listingid')
            ->join('listing_images', 'ewcart.listingid', '=', 'listing_images.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->groupBy('ewcart.cartindex')
            ->where('ewcart.userid', $id)
            ->where('ewcart.purpose', 'Cart')
            ->where('service_listings.listingtype', 'Fixed')
            ->where('ewcart.status', 'NotPurchased')
            ->get();

        $listingsVariation = DB::table('ewcart')
            ->join('service_listings', 'ewcart.listingid', '=', 'service_listings.listingid')
            ->join('listing_images', 'ewcart.listingid', '=', 'listing_images.listingid')
            ->join('listing_variations', 'ewcart.listingtypeid', '=', 'listing_variations.variationid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->groupBy('ewcart.cartindex')
            ->where('ewcart.userid', $id)
            ->where('ewcart.purpose', 'Cart')
            ->where('service_listings.listingtype', '!=', 'Fixed')
            ->where('ewcart.status', 'NotPurchased')
            ->get();

        $totPrice = DB::table('ewcart')
            ->where('ewcart.purpose', 'Cart')
            ->where('userid', $id)
            ->sum('totalprice');

        return response()->json([
            'status' => 200,
            'fixedListings' => $listingsFixed,
            'variationListings' => $listingsVariation,
            'cartprice' => $totPrice
        ]);
    }



    public function getCartByID($id)
    {
        $listingsFixed = DB::table('ewcart')
            ->join('service_listings', 'ewcart.listingid', '=', 'service_listings.listingid')
            ->join('listing_images', 'ewcart.listingid', '=', 'listing_images.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->groupBy('ewcart.cartindex')
            ->where('ewcart.cartindex', $id)
            ->where('ewcart.purpose', 'BuyItNow')
            ->where('service_listings.listingtype', 'Fixed')
            ->where('ewcart.status', 'NotPurchased')
            ->get();

        $listingsVariation = DB::table('ewcart')
            ->join('service_listings', 'ewcart.listingid', '=', 'service_listings.listingid')
            ->join('listing_images', 'ewcart.listingid', '=', 'listing_images.listingid')
            ->join('listing_variations', 'ewcart.listingtypeid', '=', 'listing_variations.variationid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->groupBy('ewcart.cartindex')
            ->where('ewcart.cartindex', $id)
            ->where('ewcart.purpose', 'BuyItNow')
            ->where('service_listings.listingtype', '!=', 'Fixed')
            ->where('ewcart.status', 'NotPurchased')
            ->get();

        $totPrice = DB::table('ewcart')
            ->where('ewcart.cartindex', $id)
            ->where('ewcart.purpose', 'BuyItNow')
            ->where('status', 'NotPurchased')
            ->sum('totalprice');

        return response()->json([
            'status' => 200,
            'fixedListings' => $listingsFixed,
            'variationListings' => $listingsVariation,
            'cartprice' => $totPrice
        ]);
    }


    public function deleteCartItem($id)
    {
        CartModel::where('cartindex', $id)->delete();
        return response()->json([
            'status' => 200,
        ]);
    }

    public function getCartItemCount($id)
    {
        $cartItemCount = DB::table('ewcart')
            ->where('status', 'NotPurchased')
            ->where('ewcart.purpose', 'Cart')
            ->where('userid', $id)
            ->get();

        return response()->json([
            'status' => 200,
            'cartCount' => $cartItemCount->count()
        ]);
    }
}
