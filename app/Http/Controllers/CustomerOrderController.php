<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CartModel;
use App\Models\CustomerOrderModel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class CustomerOrderController extends Controller
{
    public function placeOrder(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'customerid' => 'required',
            'orderstatus' => 'required',
            'remark' => 'required',
            'orderdate' => 'required',
            'ordertime' => 'required',
            'fullname' => 'required',
            'contactnumber' => 'required',
            'address' => 'required',
            'district' => 'required',
            'city' => 'required',
            'paymentoption' => 'required',
        ]);

        $token = $request->session()->token();
        $token = csrf_token();

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'validator_errors' => $validator->errors(),
            ]);
        } else {
            foreach ($request->listings as $key => $value) {
                $order = CustomerOrderModel::create([
                    'customerid' => $request->customerid,
                    'listingid' => $value['listingid'],
                    'orderstatus' => $request->orderstatus,
                    'remark' => $request->remark,
                    'orderdate' => $request->orderdate,
                    'ordertime' => $request->ordertime,
                    'fullname' => $request->fullname,
                    'contactnumber' => $request->contactnumber,
                    'address' => $request->address,
                    'district' => $request->district,
                    'city' => $request->city,
                    'paymentoption' => $request->paymentoption,
                    'listingtypeid' => $value["listingtypeid"],
                    'quantity' => $value["quantity"],
                    'totalprice' => $value["totalprice"],
                ]);

                CartModel::where('cartindex', $value["cartindex"],)
                    ->update(['status' => 'Paid']);



                $order->createToken($order->orderid . '_Token')->plainTextToken;
            }
            foreach ($request->varListings as $key => $value) {
                $order = CustomerOrderModel::create([
                    'customerid' => $request->customerid,
                    'listingid' => $value['listingid'],
                    'orderstatus' => $request->orderstatus,
                    'remark' => $request->remark,
                    'orderdate' => $request->orderdate,
                    'ordertime' => $request->ordertime,
                    'fullname' => $request->fullname,
                    'contactnumber' => $request->contactnumber,
                    'address' => $request->address,
                    'district' => $request->district,
                    'city' => $request->city,
                    'paymentoption' => $request->paymentoption,
                    'listingtypeid' => $value["listingtypeid"],
                    'quantity' => $value["quantity"],
                    'totalprice' => $value["totalprice"],
                ]);

                CartModel::where('cartindex', $value["cartindex"],)
                    ->update(['status' => 'Paid']);
                $order->createToken($order->orderid . '_Token')->plainTextToken;
            }
            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Order Added Successfully',
                ]
            );
        }
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
            ->where('userid', $id)
            ->get();

        return response()->json([
            'status' => 200,
            'cartCount' => $cartItemCount->count()
        ]);
    }

    public function getOrders()
    {

        $orders = DB::table('orders')
            ->join('customer', 'orders.customerid', '=', 'customer.customerid')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->join('services', 'partner.serviceid', '=', 'services.serviceid')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 200,
            'orders' => $orders
        ]);
    }


    public function getOrdersCount()
    {

        $orders = DB::table('orders')
            ->join('customer', 'orders.customerid', '=', 'customer.customerid')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->join('services', 'partner.serviceid', '=', 'services.serviceid')
            ->groupBy('orders.orderdate')
            ->select(
                'orders.orderdate',
                DB::raw('count(*) as total'),
                DB::raw('SUM(services.servicetype="MainService") AS MainServices'),
                DB::raw('SUM(services.servicetype="OtherService") AS EasyServices'),
            )
            ->get();

        return response()->json([
            'status' => 200,
            'orders' => $orders,
        ]);
    }



    public function getServicesOrders()
    {

        $services = DB::table('orders')
            ->join('customer', 'orders.customerid', '=', 'customer.customerid')
            ->join('service_listings', 'orders.listingid', '=', 'service_listings.listingid')
            ->join('partner', 'service_listings.partnerid', '=', 'partner.partnerid')
            ->join('services', 'partner.serviceid', '=', 'services.serviceid')
            ->groupBy('services.serviceid')
            ->select(
                'services.servicename',
                DB::raw('count(*) as total'),
                // DB::raw('SUM(services.servicetype="MainService") AS MainServices'),
                // DB::raw('SUM(services.servicetype="OtherService") AS EasyServices'),
            )
            ->get();

        return response()->json([
            'status' => 200,
            'services' => $services,
        ]);
    }
}
