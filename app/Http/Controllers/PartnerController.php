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
            $partner = DB::table('partner')->where('serviceid', $request->serviceid)->update([
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
