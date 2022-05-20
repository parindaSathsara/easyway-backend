<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminModel;
use App\Models\CustomerModel;
use App\Models\DeliveryJobModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class DeliveryJobController extends Controller
{

    public function updateDeliveryStatus(Request $request)
    {
        if (($request->orderstatus) == "ProcessByPartner") {
            DB::table('completedpartnerorders')->insert([
                'partnerid' => $request->partnerid,
                'orderid' =>$request->orderid,
                'orderdate' => $request->orderdate,
            ]);
        }
        DB::table('orders')->where('orderid', $request->orderid)->update(['orderstatus' => $request->orderstatus]);
        DB::table('deliveryjob')->where('orderid', $request->orderid)->update(['status' => $request->orderstatus]);
        return response()->json(
            [
                'status' => 200,
                'message' => 'Delivery Job Updated Successfully',
            ]
        );
    }


    public function addNewJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orderid' => 'required',
            'riderid' => 'required',
            'estimatetime' => 'required',
            'estimatedate' => 'required',
            'totaldistance' => 'required',
            'deliverytotalprice' => 'required',
            'totalPayable' => 'required'
        ]);

        $token = $request->session()->token();
        $token = csrf_token();

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'validator_errors' => $validator->errors(),
            ]);
        } else {


            $ewDelivery = DeliveryJobModel::create([
                'orderid' => $request->orderid,
                'riderid' => $request->riderid,
                'estimatetime' => $request->estimatetime,
                'estimatedate' => $request->estimatedate,
                'totaldistance' => $request->totaldistance,
                'deliverytotalprice' => $request->deliverytotalprice,
                'status' => 'RiderAccept',
                'totalPayable' => $request->totalPayable
            ]);
            DB::table('orders')->where('orderid', $request->orderid)->update(['orderstatus' => "RiderAccept"]);

            $token = $ewDelivery->createToken($ewDelivery->orderid . '_Token')->plainTextToken;

            return response()->json(
                [
                    'status' => 200,
                    'token' => $token,
                    'message' => 'Delivery Job Added Successfully',
                ]
            );
        }
    }


    // public function insert(Request $request){

    //     $validator=Validator::make($request->all(),[
    //         'username'=>'required',
    //         'email'=>'required|email',
    //         'nic'=>'required',
    //         'contact'=>'required',
    //         'password'=>'required',
    //         'district'=>'required',
    //         'role'=>'required',
    //     ]);

    //     $token = $request->session()->token();
    //     $token = csrf_token();

    //     if($validator->fails()){
    //         return response()->json([
    //             'status'=>400,
    //             'validator_errors'=>$validator->errors(),
    //         ]);
    //     }
    //     else{
    //             $ewuser=AdminModel::create([
    //                 'username'=>$request->username,
    //                 'email'=>$request->email,
    //                 'nic'=>$request->nic,
    //                 'contact'=>$request->contact,
    //                 'password'=>Hash::make($request->password),
    //                 'district'=>$request->district,
    //                 'role'=>$request->role,
    //             ]);

    //             $token = $ewuser->createToken($ewuser->email.'_Token')->plainTextToken;

    //             return response()->json(
    //                 [
    //                     'status'=>200,
    //                     'token'=>$token,
    //                     'message'=>'User Added Successfully',
    //                 ]
    //             );
    //     }

    // }



}
