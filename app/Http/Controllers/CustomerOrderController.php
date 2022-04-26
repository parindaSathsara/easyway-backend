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
}
