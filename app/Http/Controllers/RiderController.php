<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminModel;
use App\Models\RiderModel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class RiderController extends Controller
{
    public function registerRider(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rideremail' => 'required|email',
            'riderpassword' => 'required',
            'riderusername' => 'required',
            'riderdistrict' => 'required',
        ]);

        $token = $request->session()->token();
        $token = csrf_token();

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'validator_errors' => $validator->errors(),
            ]);
        } else {
            $ewrider = RiderModel::create([
                'rideremail' => $request->rideremail,
                'riderpassword' => Hash::make($request->riderpassword),
                'riderusername' => $request->riderusername,
                'riderdistrict' => $request->riderdistrict,
                'accoutnstatus' => "AccountCreated",
                'riderstatus' => "NotAvailable"
            ]);

            $token = $ewrider->createToken($ewrider->rideremail . '_Token')->plainTextToken;

            return response()->json(
                [
                    'status' => 200,
                    'token' => $token,
                    'message' => 'Rider Added Successfully',
                ]
            );
        }
    }


    public function riderUpdateProfile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'riderid' => 'required',
            'riderid' => 'required',
            'ridername' => 'required',
            'ridervehicleno' => 'required',
            'ridercontact' => 'required',
            'riderdistrict' => 'required',

            'rideremail' => 'required',
            'profilepic' => 'required',
            'riderlicense' => 'required',
            'description' => 'required',
            'riderusername' => 'required',
            'riderpassword' => 'required',

        ]);

        // $token = $request->session()->token();
        // $token = csrf_token();

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'validator_errors' => $validator->errors(),
            ]);
        } else {
            $rider = DB::table('deliveryrider')->where('riderid', $request->riderid)->update([

                'riderid' => $request->riderid,
                'ridername' => $request->ridername,
                'ridervehicleno' => $request->ridervehicleno,
                'ridercontact' => $request->ridercontact,
                'riderdistrict' => $request->riderdistrict,
                'ridernic' => $request->ridernic,
                'rideremail' => $request->rideremail,
                'profilepic' => $request->profilepic,
                'riderlicense' => $request->riderlicense,
                'description' => $request->description,
                'riderusername' => $request->riderusername,
                'riderpassword' => $request->riderpassword,
                'accountstatus' => $request->accountstatus,
                'riderstatus' => $request->riderstatus,
            ]);


            return response()->json(
                [
                    'status' => 200,
                    'rider' => $rider,
                    'message' => 'User Updated Successfully',
                ]
            );
        }
    }



    public function getRiders($id)
    {
        $rider = RiderModel::where('riderid', $id)
            ->get();


        return response()->json([
            'status' => 200,
            'rider' => $rider,
        ]);
    }


    public function getAllRiders()
    {
        $rider = RiderModel::where('accountstatus', '!=', 'AccountCreated')->get();

        return response()->json([
            'status' => 200,
            'rider' => $rider,
        ]);
    }

    public function getRidersByStatus($status)
    {
        $riders = DB::table('deliveryrider')
            ->where('deliveryrider.accountstatus', $status)
            ->get();

        return response()->json([
            'status' => 200,
            'riders' => $riders,
        ]);
    }


    public function getOrderByID($id)
    {
        $orders = DB::table('orders')
            ->join('customer', 'orders.customerid', '=', 'customer.customerid')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('listing_images', 'orders.listingid', '=', 'listing_images.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->select('*', 'partner.address as partneraddress', 'orders.address as orderaddress')
            ->groupBy('orders.orderid')
            ->where('orders.orderid', $id)
            ->get();

        $acceptedOrders = DB::table('deliveryjob')
            ->join('orders', 'deliveryjob.orderid', '=', 'orders.orderid')
            ->join('customer', 'orders.customerid', '=', 'customer.customerid')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('listing_images', 'orders.listingid', '=', 'listing_images.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->join('services', 'partner.serviceid', '=', 'services.serviceid')
            ->join('deliveryrider', 'deliveryjob.riderid', '=', 'deliveryrider.riderid')
            ->select('*', 'partner.address as partneraddress', 'orders.address as orderaddress')
            ->groupBy('orders.orderid')
            ->where('orders.orderid', $id)
            ->get();


        return response()->json([
            'status' => 200,
            'orders' => $orders,
            'acceptedOrders' => $acceptedOrders
        ]);
    }

    // public function getOrdersCount()
    // {

    //     $orders = DB::table('deliveryrider')
    //         ->groupBy('deliveryrider.riderid')
    //         ->select(
    //             DB::raw('count(*) as JobCount'),
    //         )
    //         ->get();

    //     return response()->json([
    //         'status' => 200,
    //         'orders' => $orders,
    //     ]);
    // }



    public function riderLogin(Request $request)
    {

        $user = RiderModel::where('rideremail', $request->input('rideremail'))->first();
        if (!$user || !Hash::check($request->input('riderpassword'), $user->riderpassword)) {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid User Credentials',
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'userdata' => $user,
                'message' => 'User Logged into the System Successfully !'
            ]);
        }
    }


    public function getOrdersNotCollected($id)
    {
        $deliveryrider = DB::table('deliveryrider')
            ->where('riderid', $id)
            ->get();

        $orders = DB::table('orders')
            ->join('customer', 'orders.customerid', '=', 'customer.customerid')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->join('services', 'partner.serviceid', '=', 'services.serviceid')
            ->where('orders.district', $deliveryrider[0]->riderdistrict)
            ->where('orders.orderstatus', 'OrderPlaced')
            ->get();

        $acceptedOrders = DB::table('deliveryjob')
            ->join('orders', 'deliveryjob.orderid', '=', 'orders.orderid')
            ->join('customer', 'orders.customerid', '=', 'customer.customerid')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->join('services', 'partner.serviceid', '=', 'services.serviceid')
            ->join('deliveryrider', 'deliveryjob.riderid', '=', 'deliveryrider.riderid')
            ->where('deliveryrider.riderid', $id)
            ->where('orders.orderstatus', 'RiderAccept')
            ->get();

        $pendingOrders = DB::table('deliveryjob')
            ->join('orders', 'deliveryjob.orderid', '=', 'orders.orderid')
            ->join('customer', 'orders.customerid', '=', 'customer.customerid')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->join('services', 'partner.serviceid', '=', 'services.serviceid')
            ->join('deliveryrider', 'deliveryjob.riderid', '=', 'deliveryrider.riderid')
            ->where('deliveryrider.riderid', $id)
            ->where('orders.orderstatus', 'RiderCollected')
            ->get();


        // $orders = DB::table('orders')
        //     ->join('customer', 'orders.customerid', '=', 'customer.customerid')
        //     ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
        //     ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
        //     ->join('services', 'partner.serviceid', '=', 'services.serviceid')
        //     ->where('orders.district', $deliveryrider[0]->riderdistrict)
        //     ->get();


        return response()->json([
            'status' => 200,
            'orders' => $orders,
            'acceptedOrders' => $acceptedOrders,
            'pendingToDelivery'=>$pendingOrders
        ]);
    }
}
