<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Models\Oracle\MyAccountModel;

class IndexController extends Controller
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
            $resultListCountries = MyAccountModel::listCountries($backoffice_session_id);
            
            $response = [];
            foreach($resultListCountries['result'] as $country){
                $response[] = [
                    'country_code' => $country['id'],
                    'country_name' => $country['name'],
                    'value' => $country['id'],
                    'display' => $country['name']
                ];
            }
            return response()->json([
                "status" => "OK",
                "report" => $response
            ]);
        }catch(\Exception $ex1){
            return response()->json([
                "status" => "NOK",
                "backoffice_session_id" => $backoffice_session_id
            ]);
        }
    }
}