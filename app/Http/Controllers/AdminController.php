<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    public function insert(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' =>['required','max:55'],
            'email' => 'required|email',
            'nic' => ['required','max:25'],
            'contact' => 'required',
            'password' => ['required','max:125'],
            'district' => ['required','max:50'],
            'role' => ['required','max:45'],
        ]);

        $token = $request->session()->token();
        $token = csrf_token();

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'validator_errors' => $validator->errors(),
            ]);
        } else {
            $ewuser = AdminModel::create([
                'username' => $request->username,
                'email' => $request->email,
                'nic' => $request->nic,
                'contact' => $request->contact,
                'password' => Hash::make($request->password),
                'district' => $request->district,
                'role' => $request->role,
            ]);

            $token = $ewuser->createToken($ewuser->email . '_Token')->plainTextToken;

            return response()->json(
                [
                    'status' => 200,
                    'token' => $token,
                    'message' => 'User Added Successfully',
                ]
            );
        }
    }


    
    public function getOrdersAll()
    {

        $dateC = Carbon::now()->setTimezone('GMT+5:30');
        $dateToday = Carbon::now()->setTimezone('GMT+5:30');

        $orders = DB::table('orders')
            ->join('customer', 'orders.customerid', '=', 'customer.customerid')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->join('services', 'partner.serviceid', '=', 'services.serviceid')
            ->orderBy('orders.orderdate', 'desc')
            ->get();

        $ordersLastMonth = DB::table('orders')
            ->join('customer', 'orders.customerid', '=', 'customer.customerid')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->join('services', 'partner.serviceid', '=', 'services.serviceid')
            ->whereBetween("orders.orderdate", [$dateC->startOfMonth()->format('Y-m-d'), $dateC->endOfMonth()->format('Y-m-d')])
            ->orderBy('orders.orderdate', 'desc')
            ->get();

        $ordersToday = DB::table('orders')
            ->join('customer', 'orders.customerid', '=', 'customer.customerid')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->join('services', 'partner.serviceid', '=', 'services.serviceid')
            ->where("orders.orderdate", $dateToday->today()->format('Y-m-d'))
            ->orderBy('orders.orderdate', 'desc')
            ->get();


        return response()->json([
            'status' => 200,
            'orders' => $orders,
            'ordersLastMonth' => $ordersLastMonth,
            'ordersToday' => $ordersToday,
        ]);
    }


    public function userLogin(Request $request)
    {

        $user = AdminModel::where('email', $request->input('email'))->first();
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid User Credentials',
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'username' => $user->username,
                'message' => 'User Logged into the System Successfully !'
            ]);
        }
    }

    public function getDataCounts(Request $request)
    {
        $totalOrders = DB::table('orders')
            ->get()
            ->count();

        $totalRiders = DB::table('deliveryrider')
            ->get()
            ->count();

        $totalPartners = DB::table('partner')
            ->get()
            ->count();


        $totalAdmins = DB::table('administrator')
            ->get()
            ->count();


        return response()->json([
            'status' => 200,
            'count'=>['orderCount' => $totalOrders,'riderCount' => $totalRiders,'partnerCount' => $totalPartners,'adminCount' => $totalAdmins],
        ]);
    }
}
