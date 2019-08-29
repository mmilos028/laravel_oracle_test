<?php

namespace App\Http\Controllers\Rest\Administration\UsersAndAdministrators;

use App\Http\Controllers\Controller;
use App\Models\Oracle\VoucherModel;

use Illuminate\Http\Request;
use App\Models\Oracle\AffiliateModel;

class NewUserController extends Controller
{
        
    public function listAllRoles(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        $sub_role = \Request::json()->get('sub_role', null);
        
        $resultAllRoles = AffiliateModel::getAllRoles($backoffice_session_id, $sub_role);
        
        if($resultAllRoles['status'] == 'OK'){
            return response()->json([
                "status" => "OK",
                "report" => $resultAllRoles['list_subroles'],
            ]);
        }else{
            
            return response()->json(
                [ "status" => "NOK" ]
            );
            
        }
    }
    
    public function listAffiliatesForNewUserForm(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        
        $resultGetAffiliatesForNewUserForm = AffiliateModel::getAffiliatesForNewUserForm($backoffice_session_id);
        
        if($resultGetAffiliatesForNewUserForm['status'] == 'OK'){
            return response()->json([
                "status" => "OK",
                "report" => $resultGetAffiliatesForNewUserForm['report'],
            ]);
        }else{
            
            return response()->json(
                [ "status" => "NOK" ]
            );
            
        }
    }
}