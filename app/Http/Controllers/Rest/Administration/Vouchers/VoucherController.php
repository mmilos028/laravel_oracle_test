<?php

namespace App\Http\Controllers\Rest\Administration\Vouchers;

use App\Http\Controllers\Controller;
use App\Models\Oracle\VoucherModel;

use Illuminate\Http\Request;

class VoucherController extends Controller
{
    
    public function searchPrepaidCards(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        $page_number = \Request::json()->get('page_number', 1);
        $per_page = \Request::json()->get('per_page', 1000);
        
        $serial_number = \Request::json()->get('serial_number', null);
        $affiliate_owner = \Request::json()->get('affiliate_owner', null);
        $affiliate_creator = \Request::json()->get('created_by', null);
        $used_by_player_id = \Request::json()->get('used_by_player', null);
        $player_id_bound = \Request::json()->get('player_id_bound', null); //?
        $activation_date = \Request::json()->get('activation_date', null); //?
        $amount = \Request::json()->get('amount', null);
        $prepaid_code = \Request::json()->get('prepaid_code', null); //?
        $currency = \Request::json()->get('currency', null);
        $refill_type = \Request::json()->get('promo_card', null);
        $status = \Request::json()->get('status', null);
        $creation_date = \Request::json()->get('created_date', null);
        $used_date = \Request::json()->get('date_of_use', null);
        $username = \Request::json()->get('username', null);
        $refill_allowed = \Request::json()->get('refill_status', null);
        $expire_before = \Request::json()->get('expire_before', null);
        $expire_after = \Request::json()->get('expire_after', null);
        
        if($page_number < 1) $page_number = 1;
        if($serial_number == '') $serial_number = null;
        if($affiliate_owner == '') $affiliate_owner = null;
        if($affiliate_creator == '') $affiliate_creator = null;
        if($used_by_player_id == '') $used_by_player_id = null;
        if($player_id_bound == '') $player_id_bound = null;
        if($activation_date == '') $activation_date = null;
        if($amount == '') $amount = null;
        if($prepaid_code == '') $prepaid_code = null;
        if($currency == '') $currency = null;
        if($refill_type == '') $refill_type = null;
        if($status == '') $status = null;
        if($creation_date == '') $creation_date = null;
        if($used_date == '') $used_date = null;
        if($username == '') $username = null;
        if($refill_allowed == '') $refill_allowed = null;
        if($expire_before == '') $expire_before = null;
        if($expire_after == '') $expire_after = null;
               
        $resultListPrepaidCards = VoucherModel::searchPrepaidCards(
            $backoffice_session_id,
            $page_number,
            $per_page,            
            $serial_number, $affiliate_owner,
            $affiliate_creator, $used_by_player_id, $player_id_bound, $activation_date, $amount,
            $prepaid_code, $currency, $refill_type, $status,
            $creation_date, $used_date, $username, $refill_allowed, $expire_before, $expire_after
        );
        
        if($resultListPrepaidCards['status'] == 'OK')
        {
        
            return response()->json([
                "status" => "OK",
                "report" => $resultListPrepaidCards['list_prepaid_cards'],
                "total_row_count" => $resultListPrepaidCards['total_row_count']
            ]);
        }else
        {
            return response()->json([
                "status" => "NOK"
            ]);
        }
    }
    
    public function listAvailableAmounts(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        
        return response()->json([
           "status" => "OK",
           "report" => [
               [
                   "value" => 5,
                   "text" => 5
               ],
               [
                   "value" => 10,
                   "text" => 10
               ],
               [
                   "value" => 20,
                   "text" => 20
               ],
               [
                   "value" => 50,
                   "text" => 50
               ],
               [
                   "value" => 100,
                   "text" => 100
               ],
               [
                   "value" => 200,
                   "text" => 200
               ],
               [
                   "value" => 500,
                   "text" => 500
               ],
               [
                   "value" => 1000,
                   "text" => 1000
               ],
               [
                   "value" => 2000,
                   "text" => 2000
               ],
               [
                   "value" => 5000,
                   "text" => 5000
               ],
               [
                   "value" => 10000,
                   "text" => 10000
               ]               
           ]
        ]);
    }
    
    public function listAvailableCurrency(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
              
        $resultListAvailableCurrency = VoucherModel::listAvailableCurrency( $backoffice_session_id );
        
        if($resultListAvailableCurrency['status'] == 'OK')
        {
            
            return response()->json([
                "status" => "OK",
                "report" => $resultListAvailableCurrency['list_available_currency'],
            ]);
        }else
        {
            return response()->json([
                "status" => "NOK"
            ]);
        }
    }
    
    public function listAvailableStatuses(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        
        return response()->json([
            "status" => "OK",
            "report" => [
                [
                    "value" => "I",
                    "text" => "Created"
                ],
                [
                    "value" => "A",
                    "text" => "Active"
                ],
                [
                    "value" => "B",
                    "text" => "Ban"
                ],
                [
                    "value" => "U",
                    "text" => "Used"
                ],
                [
                    "value" => "E",
                    "text" => "Expired"
                ],
            ]
        ]);
    }
    
    public function listAffiliateCreators(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        
        $resultListAffiliateCreators = VoucherModel::listAffiliateCreators( $backoffice_session_id );
        
        if($resultListAffiliateCreators['status'] == 'OK')
        {
            
            return response()->json([
                "status" => "OK",
                "report" => $resultListAffiliateCreators['list_affiliate_creators'],
            ]);
        }else
        {
            return response()->json([
                "status" => "NOK"
            ]);
        }
    }
    
    public function listAffiliateOwners(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        
        $resultListAffiliateOwners = VoucherModel::listAffiliateOwners( $backoffice_session_id );
        
        if($resultListAffiliateOwners['status'] == 'OK')
        {
            
            return response()->json([
                "status" => "OK",
                "report" => $resultListAffiliateOwners['list_affiliate_owners'],
            ]);
        }else
        {
            return response()->json([
                "status" => "NOK"
            ]);
        }
    }
    
    public function listUsedByPlayer(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        
        $resultListUsedByPlayer = VoucherModel::listUsedByPlayer( $backoffice_session_id );
        
        if($resultListUsedByPlayer['status'] == 'OK')
        {
            
            return response()->json([
                "status" => "OK",
                "report" => $resultListUsedByPlayer['list_of_players_used_by'],
            ]);
        }else
        {
            return response()->json([
                "status" => "NOK"
            ]);
        }
    }
    
    public function listAffiliatesForCurrency(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        $currency_id = \Request::json()->get('currency_id');
        //$affiliate_id = \Request::json()->get('affiliate_id');
        
        $resultSubjectIdFromSessionId = VoucherModel::getSubjectIdFromSessionId($backoffice_session_id);
        
        $affiliate_id = ($resultSubjectIdFromSessionId['affiliate_id']) ? (int) $resultSubjectIdFromSessionId['affiliate_id'] : $backoffice_session_id;
        
        $resultListAffiliatesForCurrency = VoucherModel::listAffiliatesForCurrency($backoffice_session_id, $currency_id, $affiliate_id);
        
        if($resultListAffiliatesForCurrency['status'] == 'OK')
        {
            
            return response()->json([
                "status" => "OK",
                "report" => $resultListAffiliatesForCurrency['list_of_affiliates'],
            ]);
        }else
        {
            return response()->json([
                "status" => "NOK"
            ]);
        }
    }
    
    public function createVoucherCard(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        $no_cards = \Request::json()->get('no_cards');
        $amount = \Request::json()->get('amount');
        $promo = \Request::json()->get('promo');
        $currency = \Request::json()->get('currency');
        $affiliate_id = \Request::json()->get('affiliate_id');
        $expire_date = \Request::json()->get('expire_date');
        $no_of_days = \Request::json()->get('no_of_days');
        $activate = \Request::json()->get('activate');
        $member_card = \Request::json()->get('member_card', 'N');
        $username = \Request::json()->get('username', null);
        $pass = \Request::json()->get('pass', null);
        $refill_allowed = \Request::json()->get('refill_allowed', 'N');
        $deactivate_after_spent = \Request::json()->get('deactivate_after_spent', -1);

        $resultCreatePrepaidCards = VoucherModel::createPrepaidCards($backoffice_session_id, $no_cards, 
            $amount, $promo, $currency, 
            $affiliate_id, $expire_date, $no_of_days, $activate, $member_card, 
            $username, $pass, $refill_allowed, $deactivate_after_spent);
        
        if($resultCreatePrepaidCards['status'] == 'OK' && $resultCreatePrepaidCards['status_out'] == 'OK')
        {            
            return response()->json([
                "status" => "OK",
                "status_out" => $resultCreatePrepaidCards['status_out'],
                "serial_number_start" => $resultCreatePrepaidCards['serial_number_start'], 
                "serial_number_end" => $resultCreatePrepaidCards['serial_number_end'],
            ]);
        }
        else
        {
            return response()->json([
                "status" => "NOK"
            ]);
        }
    }
    
    public function editVoucherCard(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        $serial_from = \Request::json()->get('start_serial_number');
        $serial_to = \Request::json()->get('end_serial_number');
        $promo = \Request::json()->get('promo');
        $currency = \Request::json()->get('currency');
        $affiliate_id = \Request::json()->get('affiliate_id');
        $expire_date = \Request::json()->get('expire_date');
        $no_of_days = \Request::json()->get('no_of_days');
        $status = \Request::json()->get('status');
        
        $resultEditPrepaidCards = VoucherModel::updatePrepaidCards(
            $backoffice_session_id,
            $serial_from,
            $serial_to,
            $promo,
            $currency,
            $affiliate_id,
            $expire_date,
            $no_of_days,
            $status
        );
        
        if($resultEditPrepaidCards['status'] == 'OK' && $resultEditPrepaidCards['status_out'] == 'OK' && $resultEditPrepaidCards['error_messages'] == "")
        {
            return response()->json([
                "status" => "OK",
                "status_out" => $resultEditPrepaidCards['status_out'],
                "error_messages" => $resultEditPrepaidCards['error_messages']
            ]);
        }
        else
        {
            return response()->json([
                "status" => "NOK",
                "error_messages" => $resultEditPrepaidCards['error_messages']
            ]);
        }
    }
    
    public function listPrepaidCards(Request $request)
    {
        
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        $from_serial = \Request::json()->get('start_serial_number');
        $to_serial = \Request::json()->get('end_serial_number');
        
        $arrData = VoucherModel::getPrepaidCards($backoffice_session_id, $from_serial, $to_serial);
        
        return response()->json(
            $arrData    
        );
    }
    
    public function createMemberCard(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        $no_cards = \Request::json()->get('no_cards');
        $amount = \Request::json()->get('amount');
        $promo = \Request::json()->get('promo');
        $currency = \Request::json()->get('currency');
        $affiliate_id = \Request::json()->get('affiliate_id');
        $expire_date = \Request::json()->get('expire_date');
        $no_of_days = \Request::json()->get('no_of_days');
        $activate = \Request::json()->get('activate');
        $member_card = \Request::json()->get('member_card', 'N');
        $username = \Request::json()->get('username', null);
        $pass = \Request::json()->get('pass', null);
        $refill_allowed = \Request::json()->get('refill_allowed', 'N');
        $deactivate_after_spent = \Request::json()->get('deactivate_after_spent', -1);
        
        $resultCreatePrepaidCards = VoucherModel::createPrepaidCards($backoffice_session_id, $no_cards,
            $amount, $promo, $currency,
            $affiliate_id, $expire_date, $no_of_days, $activate, $member_card,
            $username, $pass, $refill_allowed, $deactivate_after_spent);
        
        if($resultCreatePrepaidCards['status'] == 'OK' && $resultCreatePrepaidCards['status_out'] == 'OK')
        {
            return response()->json([
                "status" => "OK",
                "status_out" => $resultCreatePrepaidCards['status_out'],
                "serial_number_start" => $resultCreatePrepaidCards['serial_number_start'],
                "serial_number_end" => $resultCreatePrepaidCards['serial_number_end'],
            ]);
        }
        else
        {
            return response()->json([
                "status" => "NOK"
            ]);
        }
    }
    
    public function editMemberCard(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        $serial_from = \Request::json()->get('start_serial_number');
        $serial_to = \Request::json()->get('end_serial_number');
        $promo = \Request::json()->get('promo');
        $currency = \Request::json()->get('currency', null);
        $affiliate_id = \Request::json()->get('affiliate_id');
        $expire_date = \Request::json()->get('expire_date');
        $no_of_days = \Request::json()->get('no_of_days');
        $status = \Request::json()->get('status');
        
        $resultEditPrepaidCards = VoucherModel::updatePrepaidCards(
            $backoffice_session_id,
            $serial_from,
            $serial_to,
            $promo,
            $currency,
            $affiliate_id,
            $expire_date,
            $no_of_days,
            $status
            );
        
        if($resultEditPrepaidCards['status'] == 'OK' && $resultEditPrepaidCards['status_out'] == 'OK' && $resultEditPrepaidCards['error_messages'] == "")
        {
            return response()->json([
                "status" => "OK",
                "status_out" => $resultEditPrepaidCards['status_out'],
                "error_messages" => $resultEditPrepaidCards['error_messages']
            ]);
        }
        else
        {
            return response()->json([
                "status" => "NOK",
                "error_messages" => $resultEditPrepaidCards['error_messages']
            ]);
        }
    }
}