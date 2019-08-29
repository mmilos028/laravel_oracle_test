<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Oracle\MyAccountModel;

use Illuminate\Http\Request;
use App\Models\Oracle\AffiliateModel;

class MyAccountController extends Controller
{
    
    public function personalInformation(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        $affiliate_id = \Request::json()->get('affiliate_id');
        
        $resultUserInformation = MyAccountModel::getUserInformation($backoffice_session_id);
        
        $resultAffiliateDetails = AffiliateModel::getAffiliateDetails($backoffice_session_id, $affiliate_id);
        
        $result = array(            
            "status" => "OK",
            "user_info" => $resultUserInformation['user_info'][0],
            "affiliate_details" => $resultAffiliateDetails['subject_detail'][0],
            "user" => [
                "username"=>$resultAffiliateDetails['subject_detail'][0]['user_name'],
                "first_name"=>$resultUserInformation['user_info'][0]['first_name'],
                "last_name"=>$resultUserInformation['user_info'][0]['last_name'],
                "phone"=>$resultUserInformation['user_info'][0]['phone'],
                "email"=>$resultUserInformation['user_info'][0]['email'],
                "birthday" => $resultAffiliateDetails['subject_detail'][0]['birthday'],
                "address"=>$resultUserInformation['user_info'][0]['address'],
                "city"=>$resultUserInformation['user_info'][0]['city'],
                "zip_code"=>$resultUserInformation['user_info'][0]['zip_code'],
                "country_name"=>$resultAffiliateDetails['subject_detail'][0]['country_name'],
                "country_code"=>$resultAffiliateDetails['subject_detail'][0]['country_id'],
                //"birthday"=>$helperDateTime->getDateFormat3($userInfo['birthday']),
                "path"=>$resultAffiliateDetails['subject_detail'][0]['path'],
                "affiliate_id"=>$resultUserInformation['user_info'][0]['id'],
                "last_login"=>$resultAffiliateDetails['subject_detail'][0]['last_login'],            
                "currency"=>$resultAffiliateDetails['subject_detail'][0]['currency'],
                "language" => $resultUserInformation['user_info'][0]['bo_default_language'],
            ]
        );
        
        return response()->json($result);
    }
    
    public function savePersonalInformation(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id', null);
        $action = 'UPDATE';
        $parent_affiliate_id = null;
        $affiliate_username = null;
        $password = null;
        $subrole = null;
        $mac_address = null;
        $email = \Request::json()->get('email', 0);
        $country_id = \Request::json()->get('country_id', 0);
        $currency = null;
        $banned = 'N';
        $zip = \Request::json()->get('zip', 0);
        $phone = \Request::json()->get('phone', 0);
        $address = \Request::json()->get('address', 0);
        $birthday = \Request::json()->get('birthday', 0);
        $first_name = \Request::json()->get('first_name', 0);
        $last_name = \Request::json()->get('last_name', 0);
        $city = \Request::json()->get('city', 0);
        $affiliate_id = \Request::json()->get('affiliate_id', 0);
        $multi_currency = \Request::json()->get('multi_currency', null);
        $auto_increment = \Request::json()->get('auto_increment', null);
        $game_payback = \Request::json()->get('game_payback', null);
        $key_exit = \Request::json()->get('key_exit', null);
        $enter_password = \Request::json()->get('enter_password', null);
        $address2 = \Request::json()->get('address2', 0);
        
        $bank_account = \Request::json()->get('bank_account', null);
        $bank_country = \Request::json()->get('bank_country', null);
        $swift = \Request::json()->get('swift', null);
        $iban = \Request::json()->get('iban', null);
        $receive_mail = \Request::json()->get('receive_mail', null);
        $inactive_time = \Request::json()->get('inactive_time', null);
        $site_name = \Request::json()->get('site_name', null);
        $origin_site = 'GENUINE';
        $registred_affiliate = \Request::json()->get('registred_affiliate', null);
        $password_surf = \Request::json()->get('password_surf', null);
        $new_login_kills_sess = 'N';
        
        $result = MyAccountModel::manageUser($backoffice_session_id, $action, $parent_affiliate_id, $affiliate_username, $password, $subrole, 
            $mac_address, $email, $country_id, $currency, $banned, $zip, $phone, $address, $birthday, 
            $first_name, $last_name, $city, $affiliate_id, $multi_currency, $auto_increment, 
            $game_payback, $key_exit, $enter_password, 
            $address2, $bank_account, $bank_country, $swift, $iban,
            $receive_mail, $inactive_time, $site_name, $origin_site,
            $registred_affiliate, $password_surf, $new_login_kills_sess
        );
        
        if($result['status'] == 'OK'){
            return response()->json($result);
        }else{
            return response()->json($result);
        }
    }
}