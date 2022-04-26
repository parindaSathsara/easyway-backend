<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminModel;
use App\Models\CustomerModel;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class CustomerController extends Controller
{

    public function registerCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customername' => 'required',
            'customeremail' => 'required',
            'profilepic' => 'required',
            'customerdistrict' => 'required',
            'customerpassword' => 'required',
            'customerusername' => 'required',
            'customercontact' => 'required',
            'customerhomeaddress' => 'required',
        ]);

        $token = $request->session()->token();
        $token = csrf_token();

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'validator_errors' => $validator->errors(),
            ]);
        } else {
            $ewcustomer = CustomerModel::create([
                'customername' => $request->customername,
                'customeremail' => $request->customeremail,
                'profilepic' => $request->profilepic,
                'customerdistrict' => $request->customerdistrict,
                'customerpassword' => Hash::make($request->customerpassword),
                'customerusername' => $request->customerusername,
                'customercontact' => $request->customercontact,
                'customerhomeaddress' => $request->customerhomeaddress,
            ]);

            $token = $ewcustomer->createToken($ewcustomer->customeremail . '_Token')->plainTextToken;

            return response()->json(
                [
                    'status' => 200,
                    'token' => $token,
                    'message' => 'Customer Added Successfully',
                ]
            );
        }
    }

    public function loginCustomer(Request $request)
    {

        $user = CustomerModel::where('customeremail', $request->input('customeremail'))->first();
        if (!$user || !Hash::check($request->input('customerpassword'), $user->customerpassword)) {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid User Credentials',
            ]);
        } 
        
        else {
            return response()->json([
                'status' => 200,
                'user' => $user,
                'message' => 'User Logged into the System Successfully !'
            ]);
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
