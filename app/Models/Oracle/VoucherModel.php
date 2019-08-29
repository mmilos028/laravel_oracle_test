<?php

namespace App\Models\Oracle;

use Illuminate\Support\Facades\DB;
use App\Helpers\ErrorHelper;
use App\Helpers\ArrayHelper;

class VoucherModel
{
    
    public static function searchPrepaidCards($session_id, $page_number = 1, $rows_per_page = 100, $serial_number = null, $affiliate_owner = null, 
        $affiliate_creator = null, $used_by_player_id = null, $player_id_bound = null, $activation_date = null, $amount = null, 
        $prepaid_code = null, $currency = null, $refill_type = null, $status = null, 
        $creation_date = null, $used_date = null, $username = null, $refill_allowed = null, $expire_before = null, $expire_after = null)
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try{
            
            $statement_string = 'CALL PREPAID_CARDS.SEARCH_PREPAID_CARDS(:p_session_id_in, :p_page_number, :p_rows_per_page, :p_serial_number, :p_affiliate_owner,
            :p_affiliate_creator, :p_used_by_player_id, :p_player_id_bound, :p_activation_date, :p_amount, :p_prepaid_code, :p_currency, :p_refill_type,
            :p_status,'. "to_date(:p_creation_date, 'DD.MM.YYYY')" . ', :p_used_date, :p_username, :p_refill_allowed, '. "to_date(:p_expiry_date_before, 'DD.MM.YYYY')" .', '. "to_date(:p_expiry_date_after, 'DD.MM.YYYY')" .', :p_total_row_count, :p_prepaid_card_list_out)';
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':p_session_id_in', $session_id, \PDO::PARAM_STR);
            $stmt->bindParam(':p_page_number', $page_number, \PDO::PARAM_STR);
            $stmt->bindParam(':p_rows_per_page', $rows_per_page, \PDO::PARAM_STR);
            $stmt->bindParam(':p_serial_number', $serial_number, \PDO::PARAM_STR);
            $stmt->bindParam(':p_affiliate_owner', $affiliate_owner, \PDO::PARAM_STR);
            $stmt->bindParam(':p_affiliate_creator', $affiliate_creator, \PDO::PARAM_STR);
            $stmt->bindParam(':p_used_by_player_id', $used_by_player_id, \PDO::PARAM_STR);
            $stmt->bindParam(':p_player_id_bound', $player_id_bound, \PDO::PARAM_STR);
            $stmt->bindParam(':p_activation_date', $activation_date, \PDO::PARAM_STR);
            $stmt->bindParam(':p_amount', $amount, \PDO::PARAM_STR);
            $stmt->bindParam(':p_prepaid_code', $prepaid_code, \PDO::PARAM_STR);
            $stmt->bindParam(':p_currency', $currency, \PDO::PARAM_STR);
            $stmt->bindParam(':p_refill_type', $refill_type, \PDO::PARAM_STR);
            $stmt->bindParam(':p_status', $status, \PDO::PARAM_STR);
            $stmt->bindParam(':p_creation_date', $creation_date, \PDO::PARAM_STR);
            $stmt->bindParam(':p_used_date', $used_date, \PDO::PARAM_STR);
            $stmt->bindParam(':p_username', $username, \PDO::PARAM_STR);
            $stmt->bindParam(':p_refill_allowed', $refill_allowed, \PDO::PARAM_STR);
            $stmt->bindParam(':p_expiry_date_before', $expire_before, \PDO::PARAM_STR);
            $stmt->bindParam(':p_expiry_date_after', $expire_after, \PDO::PARAM_STR);
            
            $count = 0;
            $stmt->bindParam(':p_total_row_count', $count, \PDO::PARAM_INT);
            
            $list_prepaid_cards_cursor = "";
            $stmt->bindParam(':p_prepaid_card_list_out', $list_prepaid_cards_cursor, \PDO::PARAM_STMT);
            
            $stmt->execute();
            
            oci_execute($list_prepaid_cards_cursor, OCI_DEFAULT);
            
            $list_prepaid_cards_array = [];
            oci_fetch_all($list_prepaid_cards_cursor, $list_prepaid_cards_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($list_prepaid_cards_cursor);
            
            $list_prepaid_cards_array = ArrayHelper::changeArrayKeyLower($list_prepaid_cards_array);
            
            return [ 'status' => 'OK', 'list_prepaid_cards' => $list_prepaid_cards_array, 'total_row_count' => $count ];
        }catch(\Exception $ex){
            $connection->rollBack();
            $message = implode(" ", [
                "VoucherModel :: listPrepaidCards",
                $ex->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex->getMessage()
            ];
        }
       
    }
    
    public static function listAvailableCurrency($session_id)
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try{
            
            $statement_string = 'CALL SITE_LOGIN.CURRENCY_LIST_NEW_AFFILIATE(:session_id_in, :p_currency_out)';
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':session_id_in', $session_id, \PDO::PARAM_STR);
            
            $list_available_currency_cursor = "";
            $stmt->bindParam(':p_currency_out', $list_available_currency_cursor, \PDO::PARAM_STMT);
            
            $stmt->execute();
            
            oci_execute($list_available_currency_cursor, OCI_DEFAULT);
            
            $list_available_currency_array = [];
            oci_fetch_all($list_available_currency_cursor, $list_available_currency_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($list_available_currency_cursor);
            
            $list_available_currency_array = ArrayHelper::changeArrayKeyLower($list_available_currency_array);
                        
            return [ 'status' => 'OK', 'list_available_currency' => $list_available_currency_array ];
            
        }catch(\Exception $ex){
            $connection->rollBack();
            $message = implode(" ", [
                "VoucherModel :: listAvailableCurrency",
                $ex->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex->getMessage()
            ];
        }       
    }
    
    public static function listAffiliateCreators($session_id)
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try{
            $statement_string = 'CALL PREPAID_CARDS.LIST_OF_AFFILIATE_CREATORS(:p_affiliate_creator_out)';
            $stmt = $pdo_connection->prepare($statement_string);
                        
            $list_affiliate_creators_cursor = "";
            $stmt->bindParam(':p_affiliate_creator_out', $list_affiliate_creators_cursor, \PDO::PARAM_STMT);
            
            $stmt->execute();
            
            oci_execute($list_affiliate_creators_cursor, OCI_DEFAULT);
            
            $list_affiliate_creators_array = [];
            oci_fetch_all($list_affiliate_creators_cursor, $list_affiliate_creators_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($list_affiliate_creators_cursor);
            
            $list_affiliate_creators_array = ArrayHelper::changeArrayKeyLower($list_affiliate_creators_array);
            
            return [ 'status' => 'OK', 'list_affiliate_creators' => $list_affiliate_creators_array ];
        }catch(\Exception $ex){
            $connection->rollBack();
            $message = implode(" ", [
                "VoucherModel :: listAffiliateCreators",
                $ex->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex->getMessage()
            ];
        }    
    }
    
    public static function listAffiliateOwners($session_id)
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try{
            $statement_string = 'CALL PREPAID_CARDS.LIST_OF_AFFILIATE_OWNERS(:p_affiliate_owner_out)';
            $stmt = $pdo_connection->prepare($statement_string);
            
            $list_affiliate_owners_cursor = "";
            $stmt->bindParam(':p_affiliate_owner_out', $list_affiliate_owners_cursor, \PDO::PARAM_STMT);
            
            $stmt->execute();
            
            oci_execute($list_affiliate_owners_cursor, OCI_DEFAULT);
            
            $list_affiliate_owners_array = [];
            oci_fetch_all($list_affiliate_owners_cursor, $list_affiliate_owners_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($list_affiliate_owners_cursor);
            
            $list_affiliate_owners_array = ArrayHelper::changeArrayKeyLower($list_affiliate_owners_array);
            
            return [ 'status' => 'OK', 'list_affiliate_owners' => $list_affiliate_owners_array ];
        }catch(\Exception $ex){
            $connection->rollBack();
            $message = implode(" ", [
                "VoucherModel :: listAffiliateOwners",
                $ex->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex->getMessage()
            ];
        }
    }
    
    public static function listUsedByPlayer($session_id)
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try{
            $statement_string = 'CALL PREPAID_CARDS.LIST_OF_PLAYERS_USED_BY(:p_used_by_player_out)';
            $stmt = $pdo_connection->prepare($statement_string);
            
            $list_of_players_used_by_cursor = "";
            $stmt->bindParam(':p_used_by_player_out', $list_of_players_used_by_cursor, \PDO::PARAM_STMT);
            
            $stmt->execute();
            
            oci_execute($list_of_players_used_by_cursor, OCI_DEFAULT);
            
            $list_of_players_used_by_array = [];
            oci_fetch_all($list_of_players_used_by_cursor, $list_of_players_used_by_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($list_of_players_used_by_cursor);
            
            $list_of_players_used_by_array = ArrayHelper::changeArrayKeyLower($list_of_players_used_by_array);
            
            return [ 'status' => 'OK', 'list_of_players_used_by' => $list_of_players_used_by_array ];
        }catch(\Exception $ex){
            $connection->rollBack();
            $message = implode(" ", [
                "VoucherModel :: listUsedByPlayer",
                $ex->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex->getMessage()
            ];
        }
    }
    
    public static function listAffiliatesForCurrency($session_id, $currency_id, $affiliate_id)
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try{
            $statement_string = 'CALL PREPAID_CARDS.GET_AFFILIATE_FOR_CURRENCY_NEW(:session_id_in, :currency_id_in, :affiliate_id_in, :p_affiliate_list_out)';
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':session_id_in', $session_id, \PDO::PARAM_STR);
            $stmt->bindParam(':currency_id_in', $currency_id, \PDO::PARAM_STR);
            $stmt->bindParam(':affiliate_id_in', $affiliate_id, \PDO::PARAM_STR);
            
            $list_of_affiliates_cursor = "";
            $stmt->bindParam(':p_affiliate_list_out', $list_of_affiliates_cursor, \PDO::PARAM_STMT);
            
            $stmt->execute();
            
            oci_execute($list_of_affiliates_cursor, OCI_DEFAULT);
            
            $list_of_affiliates_array = [];
            oci_fetch_all($list_of_affiliates_cursor, $list_of_affiliates_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($list_of_affiliates_cursor);
            
            $list_of_affiliates_array = ArrayHelper::changeArrayKeyLower($list_of_affiliates_array);
            
            return [ 'status' => 'OK', 'list_of_affiliates' => $list_of_affiliates_array ];
        }catch(\Exception $ex){
            $connection->rollBack();
            $message = implode(" ", [
                "VoucherModel :: listAffiliatesForCurrency",
                $ex->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex->getMessage()
            ];
        }
    }
    
    public static function getSubjectIdFromSessionId($session_id)
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try {
            $statement_string = 'CALL CORE.M$CHECK_ADMIN_SESSION(:session_id_in, :p_affiliate_id_out)';
            $stmt = $pdo_connection->prepare($statement_string);
                        
            $stmt->bindParam(':session_id_in', $session_id, \PDO::PARAM_STR);
                        
            $affiliate_id_out = null;
            $stmt->bindParam(':p_affiliate_id_out', $affiliate_id_out, \PDO::PARAM_STR);
                        
            $stmt->execute();
            
            return [ 'status' => 'OK', 'affiliate_id' => $affiliate_id_out ];
            
        }catch(\Exception $ex){
            $connection->rollBack();
            $message = implode(" ", [
                "VoucherModel :: getSubjectIdFromSessionId",
                $ex->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex->getMessage()
            ];
        }
    }
    
    public static function createPrepaidCards(
        $session_id,
        $no_cards,
        $amount,
        $promo,
        $currency,
        $affiliate_id,
        $expire_date,
        $no_of_days,
        $activate,
        $member_card = 'N',
        $user_name = null,
        $pass = null,
        $refill_allowed = 'N',
        $deactivate_after_spent = -1
        ) 
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        try {
            $statement_string = 'CALL PREPAID_CARDS.CREATE_PREPAID_CARDS(:session_id_in, :no_cards_in, :amount_in, :promo_in, :currency_in, :affiliate_id_in, '. "to_date(:expire_date_in, 'DD.MM.YYYY')" .', :no_of_days_in, :activate_in, :member_card_in, :user_name_in, :pass_in, :refill_allowed_in, :p_deactivate_after_spent, :p_out, :p_serial_number_start_out, :p_serial_number_end_out)';
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':session_id_in', $session_id, \PDO::PARAM_STR);
            $stmt->bindParam(':no_cards_in', $no_cards, \PDO::PARAM_STR);
            $stmt->bindParam(':amount_in', $amount, \PDO::PARAM_STR);
            $stmt->bindParam(':promo_in', $promo, \PDO::PARAM_STR);
            $stmt->bindParam(':currency_in', $currency, \PDO::PARAM_STR);
            $stmt->bindParam(':affiliate_id_in', $affiliate_id, \PDO::PARAM_STR);
            $stmt->bindParam(':expire_date_in', $expire_date, \PDO::PARAM_STR);
            $stmt->bindParam(':no_of_days_in', $no_of_days, \PDO::PARAM_STR);
            $stmt->bindParam(':activate_in', $activate, \PDO::PARAM_STR);
            $stmt->bindParam(':member_card_in', $member_card, \PDO::PARAM_STR);
            $stmt->bindParam(':user_name_in', $user_name, \PDO::PARAM_STR);
            $stmt->bindParam(':pass_in', $pass, \PDO::PARAM_STR);
            $stmt->bindParam(':refill_allowed_in', $refill_allowed, \PDO::PARAM_STR);
            $stmt->bindParam(':p_deactivate_after_spent', $deactivate_after_spent, \PDO::PARAM_STR);
            $status = null;                
            $stmt->bindParam(':p_out', $status, \PDO::PARAM_STR, 255);                
            $serial_number_start = 0;
            $stmt->bindParam(':p_serial_number_start_out', $serial_number_start, \PDO::PARAM_INT);
            $serial_number_end = 0;
            $stmt->bindParam(':p_serial_number_end_out', $serial_number_end, \PDO::PARAM_INT);
            
            $stmt->execute();
            
            return array("status"=>"OK", "status_out"=>$status, 'serial_number_start'=>$serial_number_start, 'serial_number_end'=>$serial_number_end);
        }catch(\Exception $ex){
            $connection->rollBack();
            $message = implode(" ", [
                "VoucherModel :: createPrepaidCards",
                $ex->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",                
                "message" => $message,
                "exception_message" => $ex->getMessage()
            ];
        }
    }
    
    public static function updatePrepaidCards(
        $session_id, 
        $serial_from, 
        $serial_to, 
        $promo, 
        $currency, 
        $affiliate_id, 
        $expire_date, 
        $no_of_days, 
        $status
    )
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        try {
            $statement_string = 'CALL PREPAID_CARDS.EDIT_PREPAID_CARDS(:session_id_in, :serial_from_in, :serial_to_in, :affiliate_id_in, :currency_in, ' . "to_date(:expire_date_in, 'DD.MM.YYYY')" . ', :promo_in, :status_in, :no_of_days_in, :edit_status_out, :messages_out)';
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':session_id_in', $session_id, \PDO::PARAM_STR);
            $stmt->bindParam(':serial_from_in', $serial_from, \PDO::PARAM_STR);
            $stmt->bindParam(':serial_to_in', $serial_to, \PDO::PARAM_STR);
            $stmt->bindParam(':affiliate_id_in', $affiliate_id, \PDO::PARAM_STR);
            $stmt->bindParam(':currency_in', $currency, \PDO::PARAM_STR);
            $stmt->bindParam(':expire_date_in', $expire_date, \PDO::PARAM_STR);
            $stmt->bindParam(':promo_in', $promo, \PDO::PARAM_STR);
            $stmt->bindParam(':status_in', $status, \PDO::PARAM_STR);
            $stmt->bindParam(':no_of_days_in', $no_of_days, \PDO::PARAM_STR);
            $status_out = "";
            $stmt->bindParam(':edit_status_out', $status_out, \PDO::PARAM_STR, 255);
            $messages = "";
            $stmt->bindParam(':messages_out', $messages, \PDO::PARAM_STR, 255);
            $stmt->execute();
            
            return array("status" => "OK", "status_out"=>$status_out, 'error_messages'=>$messages);
        }catch(\Exception $ex){
            $connection->rollBack();
            $message = implode(" ", [
                "VoucherModel :: updatePrepaidCards",
                $ex->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex->getMessage()
            ];
        }
    }
    
    public static function getPrepaidCards($session_id, $from_serial, $to_serial)
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        try {
            $statement_string = 'CALL PREPAID_CARDS.LIST_OF_PREPAID_CARDS(:session_id_in, :from_serial_in, :to_serial_in, :c_list_cards)';
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':session_id_in', $session_id, \PDO::PARAM_STR);
            $stmt->bindParam(':from_serial_in', $from_serial, \PDO::PARAM_STR);
            $stmt->bindParam(':to_serial_in', $to_serial, \PDO::PARAM_STR);
                        
            $list_prepaid_cards_cursor = "";
            $stmt->bindParam(':c_list_cards', $list_prepaid_cards_cursor, \PDO::PARAM_STMT);
            
            $stmt->execute();
            
            oci_execute($list_prepaid_cards_cursor, OCI_DEFAULT);
            
            $list_prepaid_cards_array = [];
            oci_fetch_all($list_prepaid_cards_cursor, $list_prepaid_cards_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($list_prepaid_cards_cursor);
            
            $list_prepaid_cards_array = ArrayHelper::changeArrayKeyLower($list_prepaid_cards_array);
            
            return [ "status"=>"OK", "list_prepaid_cards"=>$list_prepaid_cards_array ];
        }catch(\Exception $ex){
            $connection->rollBack();
            $message = implode(" ", [
                "VoucherModel :: getPrepaidCards",
                $ex->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex->getMessage()
            ];
        }
    }
}