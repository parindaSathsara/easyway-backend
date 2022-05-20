<?php


namespace App\Http\Controllers;

use App\Models\ListingImagesModel;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;


use App\Models\ListingModel;
use App\Models\VariationsModel;
use Illuminate\Support\Facades\DB;

class ListingController extends Controller
{



    // public function getservices()
    // {
    //     $services = ServicesModel::all();
    //     return response()->json([
    //         'status'=> 200,
    //         'services'=>$services,
    //     ]);
    // }

    // public function updateService(Request $request)
    // {
    //     $services = DB::table('services')->where('serviceid', $request->serviceid)->update([
    //         'servicename' => $request->servicename,
    //         'servicetype' => $request->servicetype,
    //         'servicedescription' => $request->servicedescription
    //     ]);
    //     return response()->json([
    //         'status' => 200,
    //         'services'=>$services,
    //         'message' => 'Service Updated Successful'
    //     ]);
    // }



    // public function deleteService(Request $request)
    // {
    //     $services = DB::table('services')->where('serviceid',$request->serviceid)->delete();

    //     return response()->json([
    //         'status' => 200,
    //         'services'=>$services,
    //         'message' => 'Service Deleted Successful'
    //     ]);
    // }


    public function updateListing($id){
        ListingModel::where('listingid', $id)->update(['listingstatus' => "Deleted"]);
        return response()->json([
            'status' => 200,
        ]);
    }

    public function updateListingActive($id){
        ListingModel::where('listingid', $id)->update(['listingstatus' => "Active"]);
        return response()->json([
            'status' => 200,
        ]);
    }

    public function getListingsImages($id)
    {
        $listings = DB::table('listing_images')
            ->select('listing_images.listingimageurl')
            ->where('listing_images.listingid', $id)
            ->get();

        return response()->json([
            'status' => 200,
            'listings' => $listings,
        ]);
    }

    public function getListingsByID($id)
    {
        $listingType = DB::table('service_listings')
            ->where('listingid', $id)
            ->first();

        $listings = DB::table('service_listings')
            ->join('listing_images', 'service_listings.listingid', '=', 'listing_images.listingid')
            ->join('partner','service_listings.partnerid','=','partner.partnerid')
            ->join('services','partner.serviceid','=','services.serviceid')
            ->groupBy('listing_images.listingid')
            ->where('service_listings.listingid', $id)
            ->where('service_listings.listingstatus',"Active")
            
            ->limit(1)
            ->get();


        if ($listingType->listingtype == "Variation") {

            $listingvariations = DB::table('listing_variations')
                ->where('listingid', $id)
                ->get();


            return response()->json([
                'status' => 200,
                'listings' => $listings,
                'listingvariations' => $listingvariations,
                'listingtype' => $listingType->listingtype
            ]);
        } 
        
        else{
            return response()->json([
                'status' => 200,
                'listings' => $listings,
                'listingtype' => $listingType->listingtype
            ]);
        }
    }

    public function getListingsByPartnerID($id)
    {
        $listings = DB::table('service_listings')
            ->join('listing_images', 'service_listings.listingid', '=', 'listing_images.listingid')
            ->join('partner','service_listings.partnerid','=','partner.partnerid')
            ->join('services','partner.serviceid','=','services.serviceid')
            ->groupBy('listing_images.listingid')
            ->where('partner.partnerid',$id)
            ->where('service_listings.listingstatus',"Active")
            ->get();
        return response()->json([
            'status' => 200,
            'listings' => $listings,
        ]);
    }


    public function getListingsByPartnerIDAdmin()
    {
        $listings = DB::table('service_listings')
            ->join('listing_images', 'service_listings.listingid', '=', 'listing_images.listingid')
            ->join('partner','service_listings.partnerid','=','partner.partnerid')
            ->join('services','partner.serviceid','=','services.serviceid')
            ->groupBy('listing_images.listingid')
            ->where('service_listings.listingstatus',"Active")
            ->get();
        return response()->json([
            'status' => 200,
            'listings' => $listings,
        ]);
    }


    public function getDeletedListingsByPartnerIDAdmin()
    {
        $listings = DB::table('service_listings')
            ->join('listing_images', 'service_listings.listingid', '=', 'listing_images.listingid')
            ->join('partner','service_listings.partnerid','=','partner.partnerid')
            ->join('services','partner.serviceid','=','services.serviceid')
            ->groupBy('listing_images.listingid')
            ->where('service_listings.listingstatus',"Deleted")
            ->get();
        return response()->json([
            'status' => 200,
            'listings' => $listings,
        ]);
    }


    public function getListingsByServiceID($id)
    {
        $listings = DB::table('service_listings')
            ->join('listing_images', 'service_listings.listingid', '=', 'listing_images.listingid')
            ->join('partner','service_listings.partnerid','=','partner.partnerid')
            ->join('services','partner.serviceid','=','services.serviceid')
            ->groupBy('listing_images.listingid')
            ->where('services.serviceid',$id)
            ->where('service_listings.listingstatus',"Active")
            ->get();
        return response()->json([
            'status' => 200,
            'listings' => $listings,
        ]);
    }


    public function getDeletedListingsByPartnerID($id)
    {
        $listings = DB::table('service_listings')
            ->join('listing_images', 'service_listings.listingid', '=', 'listing_images.listingid')
            ->join('partner','service_listings.partnerid','=','partner.partnerid')
            ->join('services','partner.serviceid','=','services.serviceid')
            ->groupBy('listing_images.listingid')
            ->where('partner.partnerid',$id)
            ->where('service_listings.listingstatus',"Deleted")
            ->get();
        return response()->json([
            'status' => 200,
            'listings' => $listings,
        ]);
    }


    public function getListings()
    {
        $listings = DB::table('service_listings')
            ->join('listing_images', 'service_listings.listingid', '=', 'listing_images.listingid')
            ->join('partner','service_listings.partnerid','=','partner.partnerid')
            ->join('services','partner.serviceid','=','services.serviceid')
            ->groupBy('listing_images.listingid')
            ->where('service_listings.listingstatus',"Active")
            ->get();
        return response()->json([
            'status' => 200,
            'listings' => $listings,
        ]);
    }

    public function addListing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'listingtitle' => 'required',
            'listingpublishdate' => 'required',
            'listingendingdate' => 'required',
            'listingtype' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'validator_errors' => $validator->errors(),
            ]);
        } else {

            $listingModel = ListingModel::create([
                'partnerid'=>$request->partnerid,
                'listingtitle' => $request->listingtitle,
                'listingpublishdate' => $request->listingpublishdate,
                'listingendingdate' => $request->listingendingdate,
                'listingtype' => $request->listingtype,
                'listingprice' => $request->listingprice,
                'listingdescription' => $request->listingdescription,
                'listingstatus'=>'Active',
            ]);

            $listingModel->createToken($listingModel->listingtitle . '_Token')->plainTextToken;


            foreach ($request->listingimages as $key => $value) {
                $listingImageModel = ListingImagesModel::create([
                    'listingid' => $listingModel->id,
                    'listingimageurl' => $value
                ]);
                $listingImageModel->createToken($listingImageModel->listingid . '_Token')->plainTextToken;
            }

            if (($request->listingtype) == "Variation") {
                foreach ($request->listingvariations as $key => $value) {
                    $variationModel = VariationsModel::create([
                        'listingid' => $listingModel->id,
                        'variationtitle' => $request->variationtitle,
                        'variationname' => $value['variationName'],
                        'variationprice' => $value['variationPrice']
                    ]);
                    $variationModel->createToken($variationModel->variationtitle . '_Token')->plainTextToken;
                }
            }


            return response()->json(
                [
                    'listingid' => $listingModel->id,
                    'status' => 200,
                    'message' => 'Listing Added Successful',
                    'messageDescription' => $listingModel->listingdescription
                ]
            );
        }
    }
}
