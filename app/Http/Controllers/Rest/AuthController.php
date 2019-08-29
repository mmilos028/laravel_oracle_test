<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Oracle\SessionModel;
use App\Models\Oracle\AffiliateModel;
use App\Helpers\PasswordHasherHelper;
use App\Models\Oracle\MyAccountModel;
use App\Helpers\JWTHelper;


class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    
    public function loginBackoffice(Request $request)
    {
        $username = \Request::json()->get('username');
        $password = \Request::json()->get('password');
        
        $hashed_password = PasswordHasherHelper::make($password);
        
        if($username == 'LoginForCreateUser' || $username == "ITFA" || $username == "GGL_Admin_WS")
        {
            return [
                "status" => "NOK",
                "message" => 'Login failed. No user with username/password.',
            ];
        }
        
        $affiliate_banned_result = AffiliateModel::checkParentAffiliateBannedStatus($username);
        if($affiliate_banned_result['status'] == 'OK' && $affiliate_banned_result['banned_status'] == 'Y')
        {
            return [
                "status" => "NOK",
                "message" => 'Login failed. Parent affiliate is banned.',
            ];
        }
               
        $result = SessionModel::loginBackoffice(
            $username, $hashed_password
        );
                       
        if($result['status'] != 'OK')
        {
            return [
                "status" => "NOK",
                "message" => 'Login failed. No user with username/password.',
            ];
        }
        
        $backoffice_session_id = $result['session_id_out'];
        
        if($result['status'] == 'OK'){
            if($backoffice_session_id > 1){
                
                $userInformation = MyAccountModel::getUserInformation($backoffice_session_id);
                
                $affiliateLastLogin = AffiliateModel::getAffiliateDetailsLastLogin($backoffice_session_id, $userInformation['user_info'][0]['id']);
                
                $result = 
                [
                    "status" => "OK",
                    "backoffice_session_id" => $backoffice_session_id,
                    "affiliate_id" => $userInformation['user_info'][0]['id'],
                    "username" => $result['username'],
                    "result" => $result,
                    "user_info" => $userInformation['user_info'],
                    "currency_list" => $userInformation['currency_list'],
                    "affiliate_last_login_detail" => $affiliateLastLogin['subject_detail']
                ];
                
                $jwt_token = JWTHelper::generateTokenWithPayload($result);
                
                $result['jwt_token'] = $jwt_token["jwt_token"];
                
                return $result;
            }
            switch($result['session_id_out'])
            {
                case '-1':
                    return [
                        "status" => "NOK",
                        "message" => 'Login failed.',
                        "session_id_out" => $backoffice_session_id
                    ];
                    
                case '-2':
                    return [
                        "status" => "NOK",
                        "message" => 'Login failed. No user with username/password.',
                        "session_id_out" => $backoffice_session_id
                    ];

                case '-3':
                    return [
                        "status" => "NOK",
                        "message" => 'Login failed. Unknown error.',
                        "session_id_out" => $backoffice_session_id
                    ];

                case '-4':
                    return [
                        "status" => "NOK",
                        "message" => 'Already logged in backoffice. Login failed.',
                        "session_id_out" => $backoffice_session_id
                    ];
                case '-5':
                    return [
                        "status" => "NOK",
                        "message" => $username . 'is in Panic mode.',
                        "session_id_out" => $backoffice_session_id
                    ];
                case '-100000':
                    return [
                        "status" => "NOK",
                        "message" => 'Login failed. Database problem exists.',
                        "session_id_out" => $backoffice_session_id
                    ];
                default:
                     return [
                        "status" => "NOK",
                        "message" => 'Login failed. Unknown error.',
                        "session_id_out" => $backoffice_session_id,
						"result" => print_r($result, true)
                    ];
            }
        }
    }
    
    public function logoutBackoffice(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
                        
        $result = SessionModel::logoutBackoffice($backoffice_session_id);
        
        if($result['status'] == 'OK')
        {
            return [
                "status" => "OK"
            ];
        }else{
            return [
                "status" => "NOK"
            ];
        }
    }
}