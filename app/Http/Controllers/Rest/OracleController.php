<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Models\Oracle\TestModel;

class OracleController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    
    public function listCountries()
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        
        try {
            $test_data = TestModel::listCountries($backoffice_session_id);
            print_r($test_data); die;
            
            
            $response = array();
            foreach($subject_types as $key => $value){
                $response[] = [
                    "role_id" => $key,
                    "role_name" => $value
                ];
            }
            return response()->json([
                "status" => "OK",
                "list_subject_types" => $response
            ]);
        }catch(\Exception $ex1){
            return response()->json([
                "status" => "NOK",
                "backoffice_session_id" => $backoffice_session_id
            ]);
        }
    }
    
    public function test()
    {
        //die("TEST");
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        
        try {
            $test_data = TestModel::test();
            print_r($test_data); die;
            
            
            $response = array();
            foreach($subject_types as $key => $value){
                $response[] = [
                    "role_id" => $key,
                    "role_name" => $value
                ];
            }
            return response()->json([
                "status" => "OK",
                "list_subject_types" => $response
            ]);
        }catch(\Exception $ex1){
            return response()->json([
                "status" => "NOK",
				"message"=> $ex1->getMessage(),
                "backoffice_session_id" => $backoffice_session_id
            ]);
        }
    }
}