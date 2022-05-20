<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\ServicesModel as ServicesModel;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller{

    public function getservices()
    {
        $services = ServicesModel::all();
        return response()->json([
            'status'=> 200,
            'services'=>$services,
        ]);
    }


    public function getservicesByID($id)
    {
        $service = ServicesModel::where('serviceid', $id)
            ->get();

        return response()->json([
            'status' => 200,
            'service' => $service,
        ]);
    }


    public function updateService(Request $request)
    {
        $services = DB::table('services')->where('serviceid', $request->serviceid)->update([
            'servicename' => $request->servicename,
            'servicetype' => $request->servicetype,
            'servicedescription' => $request->servicedescription
        ]);
        return response()->json([
            'status' => 200,
            'services'=>$services,
            'message' => 'Service Updated Successful'
        ]);
    }


    
    public function deleteService(Request $request)
    {
        $services = DB::table('services')->where('serviceid',$request->serviceid)->delete();
        
        return response()->json([
            'status' => 200,
            'services'=>$services,
            'message' => 'Service Deleted Successful'
        ]);
    }


    public function addService(Request $request){


        $validator=Validator::make($request->all(),[
            'servicename'=>'required',
            'servicetype'=>'required',
            'servicedescription'=>'required'
        ]);

        $token=$request->session()->token();
        $token=csrf_token();


        if($validator->fails()){
            return response()->json([
                'status'=>400,
                'validator_errors'=>$validator->errors(),
            ]);
        }

        else{
            $service=ServicesModel::create([
                'servicename'=>$request->servicename,
                'servicetype'=>$request->servicetype,
                'servicedescription'=>$request->servicedescription
            ]);

            $token=$service->createToken($service->servicename.'_Token')->plainTextToken;

            return response()->json(
                [
                    'status'=>200,
                    'token'=>$token,
                    'message'=>'Service Added Successful'
                ]
            );
        }

    }


}