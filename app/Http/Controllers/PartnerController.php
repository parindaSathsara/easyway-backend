<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminModel;
use App\Models\PartnerModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class PartnerController extends Controller
{

    public function getAllPartners()
    {
        $partner = PartnerModel::where('accountstatus', '!=', 'AccountCreated')->get();

        return response()->json([
            'status' => 200,
            'partners' => $partner,
        ]);
    }
    public function getPartnersByStatus($status)
    {
        $partner = DB::table('partner')
            ->join('services', 'partner.serviceid', '=', 'services.serviceid')
            ->where('partner.accountstatus', $status)
            ->get();

        return response()->json([
            'status' => 200,
            'partners' => $partner,
        ]);
    }


    public function getPartners($id)
    {
        $partner = PartnerModel::where('partnerid', $id)
            ->get();


        return response()->json([
            'status' => 200,
            'partners' => $partner,
        ]);
    }


    public function getPartnerDataCount($id)
    {

        $totalOrders = DB::table('orders')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->where('partner.partnerid', $id)
            ->get();

        $totalListings = DB::table('service_listings')
            ->where('partnerid', $id)
            ->get();

        $totalSales = DB::table('orders')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->where('partner.partnerid', $id)
            ->groupBy('partner.partnerid')
            ->select(
                DB::raw('SUM(orders.totalprice) AS TotPrice'),
            )
            ->get();

        $sales=$totalSales->count()==0?[['TotPrice'=>0.00]]:$totalSales;
        

    
        return response()->json([
            'status' => 200,
            'count' => ['totalOrders' => $totalOrders->count(), 'servicesListings' => $totalListings->count(), 'totalSales' => $sales[0]],
        ]);
    }


    public function getRecentOrdersPartners($id)
    {

        $orders = DB::table('orders')
            ->join('customer', 'orders.customerid', '=', 'customer.customerid')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->join('services', 'partner.serviceid', '=', 'services.serviceid')
            ->where('partner.partnerid',$id)
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 200,
            'orders' => $orders
        ]);
    }


    public function getPartnerSales()
    {

        $orders = DB::table('orders')
            ->join('customer', 'orders.customerid', '=', 'customer.customerid')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->join('services', 'partner.serviceid', '=', 'services.serviceid')
            ->groupBy('orders.orderdate')
            ->select(
                'orders.orderdate',
                DB::raw('SUM(orders.totalprice) AS totalprice'),
            )
            ->get();

        return response()->json([
            'status' => 200,
            'orders' => $orders,
        ]);
    }





    public function partnerRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'serviceid' => 'required',
            'district' => 'required',
            'username' => 'required',
            'password' => 'required',
            'email' => 'required|email',
        ]);

        $token = $request->session()->token();
        $token = csrf_token();

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'validator_errors' => $validator->errors(),
            ]);
        } else {
            $ewpartner = PartnerModel::create([
                'serviceid' => $request->serviceid,
                'district' => $request->district,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'accountstatus' => 'AccountCreated',
                'servicestatus' => 'Closed',
            ]);

            $token = $ewpartner->createToken($ewpartner->email . '_Token')->plainTextToken;

            return response()->json(
                [
                    'status' => 200,
                    'token' => $token,
                    'message' => 'User Added Successfully',
                ]
            );
        }
    }



    public function partnerUpdateAccount(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'serviceid' => 'required',
            'partnername' => 'required',
            'contactnumber' => 'required',
            'address' => 'required',
            'servicestarttime' => 'required',
            'serviceendtime' => 'required',
            'district' => 'required',
            'profilepic' => 'required',
            'username' => 'required',
            'password' => 'required',
            'email' => 'required',
            'nic' => 'required',
            'brcopy' => 'required',
            'accountstatus' => 'required',
            'description' => 'required'
        ]);

        // $token = $request->session()->token();
        // $token = csrf_token();

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'validator_errors' => $validator->errors(),
            ]);
        } else {
            $partner = DB::table('partner')->where('partnerid', $request->partnerid)->update([
                'serviceid' => $request->serviceid,
                'partnername' => $request->partnername,
                'contactnumber' => $request->contactnumber,
                'address' => $request->address,
                'servicestarttime' => $request->servicestarttime,
                'serviceendtime' => $request->serviceendtime,
                'district' => $request->district,
                'profilepic' => $request->profilepic,
                'username' => $request->username,
                'password' => $request->password,
                'email' => $request->email,
                'nic' => $request->nic,
                'brcopy' => $request->brcopy,
                'accountstatus' => $request->accountstatus,
                'servicestatus' => $request->servicestatus,
                'description' => $request->description
            ]);


            return response()->json(
                [
                    'status' => 200,
                    'partner' => $partner,
                    'message' => 'User Updated Successfully',
                ]
            );
        }
    }



    public function partnerLogin(Request $request)
    {

        $user = PartnerModel::where('email', $request->input('email'))->first();
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid User Credentials',
                'hash' => $request->input('password')

            ]);
        } else {
            return response()->json([
                'status' => 200,
                'username' => $user->username,
                'userdata' => $user,
                'message' => 'User Logged into the System Successfully !'
            ]);
        }
    }
}
