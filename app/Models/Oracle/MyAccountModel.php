<?php
namespace App\Models\Oracle;

use App\Helpers\ErrorHelper;
use App\Helpers\ArrayHelper;

use Illuminate\Support\Facades\DB;

class MyAccountModel
{
    
    /**
     * new user, player, affiliate, terminal player, other... - MANAGE AFFILIATE
     * @param $backoffice_session_id
     * @param $action
     * @param $parent_affiliate_id
     * @param $affiliate_username
     * @param $password
     * @param $subrole
     * @param $mac_address
     * @param $email
     * @param $country_id
     * @param $currency
     * @param $banned
     * @param $zip
     * @param $phone
     * @param $address
     * @param $birthday
     * @param $first_name
     * @param $last_name
     * @param $city
     * @param $affiliate_id
     * @param $multicurrency
     * @param $autoincrement
     * @param $game_payback
     * @param $key_exit
     * @param $enter_password
     * @param null $street_address2
     * @param null $bank_account
     * @param null $bank_country
     * @param null $swift
     * @param null $iban
     * @param null $receive_mail
     * @param null $inactive_time
     * @param null $site_name
     * @param string $origin_site
     * @param null $registred_affiliate
     * @param null $password_surf
     * @param string $new_login_kills_sess
     */
    public static function manageUser(
        $backoffice_session_id,
        $action,
        $parent_affiliate_id,
        $affiliate_username,
        $password,
        $subrole,
        $mac_address,
        $email,
        $country_id,
        $currency,
        $banned,
        $zip,
        $phone,
        $address,
        $birthday,
        $first_name,
        $last_name,
        $city,
        $affiliate_id,
        $multi_currency,
        $auto_increment,
        $game_payback,
        $key_exit,
        $enter_password,
        $street_address2 = null,
        $bank_account = null,
        $bank_country = null,
        $swift = null,
        $iban = null,
        $receive_mail = null,
        $inactive_time = null,
        $site_name = null,
        $origin_site = 'GENUINE',
        $registred_affiliate = null,
        $password_surf = null,
        $new_login_kills_sess = 'N'
        ) {
            $connection = DB::connection('oracle');
            $pdo_connection = $connection->getPdo();
        
            $registred_affiliate = 'N';
            
            $message = "MANAGMENT_CORE.M_DOLAR_MANAGE_AFFILIATES(p_session_id_in = $backoffice_session_id, p_action_in = $action, p_aff_for_in = $aff_for,
			p_name_new_in = $affiliate_username, p_password_in = $password, p_affiliates_type_in = $subrole,
			p_mac_address_in = $mac_address, p_email_in = $email, p_country_in = $country_id,
			p_currency_in = $currency, p_banned_in = $banned, p_zip_code_in = $zip,
			p_phone_in = $phone, p_address_in = $address, p_birthday_in = $birthday,
			p_first_name_in = $first_name, p_last_name_in = $last_name, p_city_in = $city,
			p_subject_id_in = $affiliate_id, p_multi_currency_in = $multi_currency,
			p_auto_credits_increment_in = $auto_increment, p_pay_back_perc = $game_payback,
			p_key_exit_in = $key_exit, p_enter_pass_in = $enter_password, p_ADDRESS2_in = $street_address2,
			p_BANK_ACCOUNT_in = $bank_account, p_BANK_COUNTRY_in = $bank_country,
			p_SWIFT_in = $swift, p_IBAN_in = $iban, p_send_mail_in = $receive_mail,
			p_inactive_time_in = $inactive_time, p_site_name_in = $site_name, p_registred_aff = $registred_affiliate, p_origin_in = $origin_site,
		    p_new_login_kills_sess = $new_login_kills_sess, p_subject_id_dummy_out = null)";
            ErrorHelper::writeInfo($message, $message);

            try {
                $statement_string = 'CALL MANAGMENT_CORE.M$MANAGE_AFFILIATES(:p_session_id_in, :p_action_in, :p_aff_for_in, :p_name_new_in, :p_password_in, :p_password_in_surf, :p_affiliates_type_in, :p_mac_address_in, :p_email_in, :p_country_in, :p_currency_in, :p_banned_in, :p_zip_code_in, :p_phone_in, :p_address_in, :p_birthday_in, :p_first_name_in, :p_last_name_in, :p_city_in, :p_subject_id_in, :p_multi_currency_in, :p_auto_credits_increment_in, :p_pay_back_perc, :p_key_exit_in, :p_enter_pass_in, :p_ADDRESS2_in, :p_BANK_ACCOUNT_in, :p_BANK_COUNTRY_in, :p_SWIFT_in, :p_IBAN_in, :p_send_mail_in, :p_inactive_time_in, :p_site_name_in, :p_registred_aff, :p_origin_in, :p_new_login_kills_sess, :p_subject_id_dummy_out)';
                $stmt = $pdo_connection->prepare($statement_string);
                
                $stmt->bindParam(':p_session_id_in', $backoffice_session_id, \PDO::PARAM_STR);
                $stmt->bindParam(':p_action_in', $action, \PDO::PARAM_STR);
                $stmt->bindParam(':p_aff_for_in', $parent_affiliate_id, \PDO::PARAM_STR);
                $stmt->bindParam(':p_name_new_in', $affiliate_username, \PDO::PARAM_STR);
                $stmt->bindParam(':p_password_in', $password, \PDO::PARAM_STR);
                $stmt->bindParam(':p_password_in_surf', $password_surf, \PDO::PARAM_STR);
                $stmt->bindParam(':p_affiliates_type_in', $subrole, \PDO::PARAM_STR);
                $stmt->bindParam(':p_mac_address_in', $mac_address, \PDO::PARAM_STR);
                $stmt->bindParam(':p_email_in', $email, \PDO::PARAM_STR);
                $stmt->bindParam(':p_country_in', $country_id, \PDO::PARAM_STR);
                $stmt->bindParam(':p_currency_in', $currency, \PDO::PARAM_STR);
                $stmt->bindParam(':p_banned_in', $banned, \PDO::PARAM_STR);
                $stmt->bindParam(':p_zip_code_in', $zip, \PDO::PARAM_STR);
                $stmt->bindParam(':p_phone_in', $phone, \PDO::PARAM_STR);
                $stmt->bindParam(':p_address_in', $address, \PDO::PARAM_STR);
                $stmt->bindParam(':p_birthday_in', $birthday, \PDO::PARAM_STR);
                $stmt->bindParam(':p_first_name_in', $first_name, \PDO::PARAM_STR);
                $stmt->bindParam(':p_last_name_in', $last_name, \PDO::PARAM_STR);
                $stmt->bindParam(':p_city_in', $city, \PDO::PARAM_STR);
                $stmt->bindParam(':p_subject_id_in', $affiliate_id, \PDO::PARAM_STR);
                $stmt->bindParam(':p_multi_currency_in', $multi_currency, \PDO::PARAM_STR);
                $stmt->bindParam(':p_auto_credits_increment_in', $auto_increment, \PDO::PARAM_STR);
                $stmt->bindParam(':p_pay_back_perc', $game_payback, \PDO::PARAM_STR);
                $stmt->bindParam(':p_key_exit_in', $key_exit, \PDO::PARAM_STR);
                $stmt->bindParam(':p_enter_pass_in', $enter_password, \PDO::PARAM_STR);
                $stmt->bindParam(':p_ADDRESS2_in', $street_address2, \PDO::PARAM_STR);
                $stmt->bindParam(':p_BANK_ACCOUNT_in', $bank_account, \PDO::PARAM_STR);
                $stmt->bindParam(':p_BANK_COUNTRY_in', $bank_country, \PDO::PARAM_STR);
                $stmt->bindParam(':p_SWIFT_in', $swift, \PDO::PARAM_STR);
                $stmt->bindParam(':p_IBAN_in', $iban, \PDO::PARAM_STR);
                $stmt->bindParam(':p_send_mail_in', $receive_mail, \PDO::PARAM_STR);
                $stmt->bindParam(':p_inactive_time_in', $inactive_time, \PDO::PARAM_STR);
                $stmt->bindParam(':p_site_name_in', $site_name, \PDO::PARAM_STR);
                $stmt->bindParam(':p_registred_aff', $registred_affiliate, \PDO::PARAM_STR);
                $stmt->bindParam(':p_origin_in', $origin_site, \PDO::PARAM_STR);
                $stmt->bindParam(':p_new_login_kills_sess', $new_login_kills_sess, \PDO::PARAM_STR);
                $subject_id_out = null;
                $stmt->bindParam(':p_subject_id_dummy_out', $subject_id_out, \PDO::PARAM_STR, 255);
                
                $stmt->execute();
                
                $connection->commit();
                
                return ["status"=>'OK', "subject_id"=>$subject_id_out];
                
            }catch(\Exception $ex1){
                $connection->rollBack();
                $message = implode(" ", [
                    $ex1->getMessage()
                ]);
                
                ErrorHelper::writeError($message, $message);
                
                return [
                    "status" => "NOK",
                    "message" => $message,
                    "exception_message" => $ex1->getMessage()
                ];
            }
    }
    
    /**
     * 
     * @param string $session_id
     * @param number $subject_id
     * @return array[]|string[]|NULL[]
     */
    public static function getUserInformation($session_id, $subject_id = 0)
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try {
            $statement_string = 'CALL MANAGMENT_CORE.M$USER_INFO(:p_session_id_in, :p_subject_id_in, :p_user_info_out, :p_currency_list_out)';
            $stmt = $pdo_connection->prepare($statement_string);
           
            $stmt->bindParam(':p_session_id_in', $session_id, \PDO::PARAM_STR);
            $stmt->bindParam(':p_subject_id_in', $subject_id, \PDO::PARAM_STR);
            
            $user_info_cursor = "";
            $stmt->bindParam(':p_user_info_out', $user_info_cursor, \PDO::PARAM_STMT);
            
            $currency_list_cursor = "";
            $stmt->bindParam(':p_currency_list_out', $currency_list_cursor, \PDO::PARAM_STMT);
            
            $stmt->execute();
                        
            oci_execute($user_info_cursor, OCI_DEFAULT);
            $user_info_array = [];
            oci_fetch_all($user_info_cursor, $user_info_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($user_info_cursor);
            
            $user_info_array = ArrayHelper::changeArrayKeyLower($user_info_array);
            
            
            oci_execute($currency_list_cursor, OCI_DEFAULT);
            $currency_list_array = [];
            oci_fetch_all($currency_list_cursor, $currency_list_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($currency_list_cursor);
            
            $currency_list_array = ArrayHelper::changeArrayKeyLower($currency_list_array);
                                   
            return array("user_info" => $user_info_array, "currency_list" => $currency_list_array);
        }catch(\PDOException $ex1){
            $pdo_connection->rollBack();
            //$connection->disconnect();
            
            $message = implode(" ", [
                "MyAccountModel :: getUserInformation",
                $ex1->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex1->getMessage()
            ];
        }catch(\Exception $ex2){
            $pdo_connection->rollBack();
            //$connection->disconnect();
            
            $message = implode(" ", [
                "MyAccountModel :: getUserInformation",
                $ex2->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex2->getMessage()
            ];
        }
    }
    
    public static function listCountries($backoffice_session_id){
        
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try{
            
            $pdo_connection->beginTransaction();
            $statement_string = "CALL MANAGMENT_CORE.M\$LIST_COUNTRIES(:p_session_id_in, :p_countries_list_out)";
            $stmt = $pdo_connection->prepare($statement_string);
            $stmt->bindParam(':p_session_id_in', $backoffice_session_id, \PDO::PARAM_INT);
            $list_countries_cursor = "";
            $stmt->bindParam(':p_countries_list_out', $list_countries_cursor, \PDO::PARAM_STMT);
            $stmt->execute();
            
            oci_execute($list_countries_cursor, OCI_DEFAULT);
            
            $list_countries_array = [];
            oci_fetch_all($list_countries_cursor, $list_countries_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($list_countries_cursor);
            
            $list_countries_array = ArrayHelper::changeArrayKeyLower($list_countries_array);
                       
            return [
                "status" => "OK",
                "result" => $list_countries_array
            ];
        }catch(\PDOException $ex1){
            $connection->rollBack();
            $message = implode(" ", [
                $ex1->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex1->getMessage()
            ];
        }catch(\Exception $ex2){
            $connection->rollBack();
            $message = implode(" ", [
                $ex2->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex2->getMessage()
            ];
        }
    }
}