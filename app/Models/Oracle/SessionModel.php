<?php
namespace App\Models\Oracle;

use App\Helpers\IPAddressHelper;
use App\Helpers\ErrorHelper;
use App\Helpers\ArrayHelper;

use Illuminate\Support\Facades\DB;

class SessionModel
{
    
    private static $DEBUG = false;
    
    /**
     * 
     * @param string $username
     * @param string $password
     * @return string[]|string[]|NULL[]
     */
    public static function loginBackoffice($username, $password){
        /*
        $session_type_name_result = SubjectTypesModel::getSubjectType("MANAGMENT_TYPES.NAME_IN_BACK_OFFICE");
        $session_type_name_value = $session_type_name_result['value'];
        
        return [ "status" => "OK", "result" => $session_type_name_result];
        */
        
        $ip_address = IPAddressHelper::getRealIPAddress();
        
        if(self::$DEBUG){
            $message = "CALL MANAGMENT_CORE.M\$LOGIN_USER(:p_username_in = {$username}, :p_password_in = {$password}, 
                :p_ip_address_in = {$ip_address}, :p_country_name_in,
                :p_city_in, :p_session_type_name_in, :p_origin_in, :p_session_out, :p_currency_out, :p_multi_currency_out,
                :p_auto_credit_increment_out, :p_auto_credit_increment_y_out, :p_subject_type_id_out, :p_subject_type_name_out,
                :p_subject_super_type_id_out, :p_subject_super_type_name_out, :p_session_type_id_out, :p_session_type_name_out,
                :p_first_name_out, :p_last_name_out, :p_last_time_collect_out, :p_online_casino_out)";
            
            ErrorHelper::writeError($message, $message);
        }
        
        $connection = DB::connection('oracle');
        
        $pdo_connection = $connection->getPdo();
                        
        try{
            
            $stmt = $pdo_connection->prepare("alter session set nls_date_format = 'dd-Mon-yyyy hh24:mi:ss'");
            $stmt->execute();
                                   
            $statement_string = 'CALL MANAGMENT_CORE.M$LOGIN_USER(:p_username_in, :p_password_in, :p_ip_address_in, :p_country_name_in, 
            :p_city_in, :p_session_type_name_in, :p_origin_in, :p_session_out, :p_currency_out, :p_multi_currency_out, 
            :p_auto_credit_increment_out, :p_auto_credit_increment_y_out, :p_subject_type_id_out, :p_subject_type_name_out, 
            :p_subject_super_type_id_out, :p_subject_super_type_name_out, :p_session_type_id_out, :p_session_type_name_out, 
            :p_first_name_out, :p_last_name_out, :p_last_time_collect_out, :p_online_casino_out)';
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':p_username_in', $username);
            $stmt->bindParam(':p_password_in', $password);
            
            $stmt->bindParam(':p_ip_address_in', $ip_address, \PDO::PARAM_STR);
            
            $country_name = '';
            $stmt->bindParam(':p_country_name_in', $country_name, \PDO::PARAM_STR);
            
            $city = '';
            $stmt->bindParam(':p_city_in', $city, \PDO::PARAM_STR);
            
            $session_type_name_result = SubjectTypeModel::getSubjectType("MANAGMENT_TYPES.NAME_IN_BACK_OFFICE");
            $session_type_name_value = $session_type_name_result['value'];
            //$session_type_name_value = "Back Office";
            
            $stmt->bindParam(':p_session_type_name_in', $session_type_name_value, \PDO::PARAM_STR);
            
            $origin_site = config("constants.GENUINE");
            
            $stmt->bindParam(':p_origin_in', $origin_site, \PDO::PARAM_STR);
            
            $session_id_out = "";
            $stmt->bindParam(':p_session_out', $session_id_out, \PDO::PARAM_STR, 255);
            
            $currency_out = "";
            $stmt->bindParam(':p_currency_out', $currency_out, \PDO::PARAM_STR, 255);
            
            $multi_currency_out = "";
            $stmt->bindParam(':p_multi_currency_out', $multi_currency_out, \PDO::PARAM_STR, 255);
            
            $auto_credit_increment_out = "";
            $stmt->bindParam(':p_auto_credit_increment_out', $auto_credit_increment_out, \PDO::PARAM_STR, 255);
            
            $auto_credit_increment_y_out = "";
            $stmt->bindParam(':p_auto_credit_increment_y_out', $auto_credit_increment_y_out, \PDO::PARAM_STR, 255);
                        
            $subject_type_id_out = "";
            $stmt->bindParam(':p_subject_type_id_out', $subject_type_id_out, \PDO::PARAM_STR, 255);
            
            $subject_type_name_out = "";
            $stmt->bindParam(':p_subject_type_name_out', $subject_type_name_out, \PDO::PARAM_STR, 255);
            
            $subject_super_type_id_out = "";
            $stmt->bindParam(':p_subject_super_type_id_out', $subject_super_type_id_out, \PDO::PARAM_STR, 255);
            
            $subject_super_type_name_out = "";
            $stmt->bindParam(':p_subject_super_type_name_out', $subject_super_type_name_out, \PDO::PARAM_STR, 255);
            
            $session_type_id_out = "";
            $stmt->bindParam(':p_session_type_id_out', $session_type_id_out, \PDO::PARAM_STR, 255);
            
            $session_type_name_out = "";
            $stmt->bindParam(':p_session_type_name_out', $session_type_name_out, \PDO::PARAM_STR, 255);
            
            $first_name_out = "";
            $stmt->bindParam(':p_first_name_out', $first_name_out, \PDO::PARAM_STR, 255);
            
            $last_name_out = "";
            $stmt->bindParam(':p_last_name_out', $last_name_out, \PDO::PARAM_STR, 255);
            
            $last_time_collect_out = "";
            $stmt->bindParam(':p_last_time_collect_out', $last_time_collect_out, \PDO::PARAM_STR, 255);
            
            $online_casino_out = "";
            $stmt->bindParam(':p_online_casino_out', $online_casino_out, \PDO::PARAM_STR, 255);
            
            $stmt->execute();
            
            $connection->commit();
                             
            //$connection->disconnect();
                                  
            return [
                "status" => "OK",
                "username" => $username,
                //"password" => $password,
                "backoffice_session_type_name" => $session_type_name_value,
                "session_id_out" => $session_id_out,
                "currency_out" => $currency_out,
                "multi_currency_out" => $multi_currency_out,
                "auto_credit_increment_out" => $auto_credit_increment_out,
                "auto_credit_increment_y_out" => $auto_credit_increment_y_out,
                "subject_type_id_out" => $subject_type_id_out,
                "subject_type_name_out" => $subject_type_name_out,
                "subject_super_type_id_out" => $subject_super_type_id_out,
                "subject_super_type_name_out" => $subject_super_type_name_out,
                "session_type_id_out" => $session_type_id_out,
                "session_type_name_out" => $session_type_name_out,
                "first_name_out" => $first_name_out,
                "last_name_out" => $last_name_out,
                "last_time_collect_out" => $last_time_collect_out,
                "online_casino_out" => $online_casino_out            
            ];
        }catch(\PDOException $ex1){
            $pdo_connection->rollBack();
            //$connection->disconnect();
            
            $message = implode(" ", [
                "SessionModel :: loginBackoffice",
                $ex1->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_type" => "PDOException",
                "exception_message" => $ex1->getTraceAsString()
            ];
        }catch(\Exception $ex2){
            $pdo_connection->rollBack();
            //$connection->disconnect();
            
            $message = implode(" ", [
                "SessionModel :: loginBackoffice",
                $ex2->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_type" => "Exception",
                "exception_message" => $ex2->getTraceAsString()
            ];
        }
    }
    
    /**
     * 
     * @param string $session_id
     * @param number $subject_id
     * @param string $broken
     * @return string[]|string[]|NULL[]
     */
    public static function logoutBackoffice($session_id, $subject_id = 0, $broken = "N")
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try {
            
            $statement_string = 'CALL MANAGMENT_CORE.M$CLOSE_SESSION(:p_session_id_in, :p_subject_id_in, :p_broken_in)';
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':p_session_id_in', $session_id, \PDO::PARAM_STR);
            $stmt->bindParam(':p_subject_id_in', $subject_id, \PDO::PARAM_STR);
            $stmt->bindParam(':p_broken_in', $broken, \PDO::PARAM_STR);
            
            $stmt->execute();
            
            //$connection->disconnect();
            
            return [ "status" => "OK" ];
            
        } catch(\PDOException $ex1){
            $pdo_connection->rollBack();
            //$connection->disconnect();
            
            $message = implode(" ", [
                "SessionModel :: logoutBackoffice",
                $ex1->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex1->getMessage()
            ];
        } catch(\Exception $ex2){
            $pdo_connection->rollBack();
            //$connection->disconnect();
            
            $message = implode(" ", [
                "SessionModel :: logoutBackoffice",
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
    
    /**
     * 
     * @param string $session_id
     * @return string[]|array[]|string[]|NULL[]
     */
    public static function getCurrencyForSubjects($session_id)
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try {
            $statement_string = 'CALL REPORTS.M$LIST_CURRENCY_FOR_SESSIONS(:p_session_id_in, :p_currency_out)';
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':p_session_id_in', $session_id, \PDO::PARAM_STR);
            
            $list_currency_cursor = "";
            $stmt->bindParam(':p_currency_out', $list_currency_cursor, \PDO::PARAM_STMT);
            
            $stmt->execute();
            
            oci_execute($list_currency_cursor, OCI_DEFAULT);
            
            $list_currency_array = [];
            oci_fetch_all($list_currency_cursor, $list_currency_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($list_currency_cursor);
            
            $list_currency_array = ArrayHelper::changeArrayKeyLower($list_currency_array);
            
            return [
                "status" => "OK",
                "list_currency" => $list_currency_array
            ];
        } catch(\PDOException $ex1){
            $pdo_connection->rollBack();
            //$connection->disconnect();
            
            $message = implode(" ", [
                "SessionModel :: getCurrencyForSubjects",
                $ex1->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex1->getMessage()
            ];
        } catch(\Exception $ex2){
            $pdo_connection->rollBack();
            //$connection->disconnect();
            
            $message = implode(" ", [
                "SessionModel :: getCurrencyForSubjects",
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
    
    
    /**
     * 
     * @param string $session_id
     * @return string[]
     */
    public static function validateSession($session_id)
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        $yes_no = 'N';
        $remaining_seconds = 0;
        try {
            $statement_string = 'CALL MANAGMENT_CORE.M$VALIDATE_SESSION(:p_session_id_in, :p_yes_no_out, :p_remaining_seconds_out)';
            $stmt = $pdo_connection->prepare($statement_string);

            $stmt->bindParam(':p_session_id_in', $session_id, \PDO::PARAM_STR);
            $stmt->bindParam(":p_yes_no_out", $yes_no, \PDO::PARAM_STR, 5);
            $stmt->bindParam(":p_remaining_seconds_out", $remaining_seconds, \PDO::PARAM_STR, 255);
            $stmt->execute();
                        
            return [ 'status' => 'OK', 'yes_no' => $yes_no ];
            
        } catch(\Exception $ex1){
            $pdo_connection->rollBack();
            
            $message = implode(" ", [
                "SessionModel :: validateSession",
                $ex1->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            $yes_no = 'Y';
            return [ 'status' => 'OK', 'yes_no' => $yes_no ];
        }
    }
    
    /**
     * for every user action in backoffice perform session validation test
     * @param $session_id
     * @return array
     */
    public static function pingSession($session_id)
    {        
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        $yes_no = 'N';
        $remaining_seconds = 0;
        
        try {
            $statement_string = 'CALL MANAGMENT_CORE.M$VALIDATE_SESSION(:p_session_id_in, :p_yes_no_out, :p_remaining_seconds_out)';
            $stmt = $pdo_connection->prepare($statement_string);
            //
            $stmt->bindParam(':p_session_id_in', $session_id, \PDO::PARAM_STR);
            $stmt->bindParam(":p_yes_no_out", $yes_no, \PDO::PARAM_STR, 5);
            $stmt->bindParam(":p_remaining_seconds_out", $remaining_seconds, \PDO::PARAM_STR, 255);
            $stmt->execute();
            
            return [ "status" => 'OK', "yes_no_status" => $yes_no, "remaining_seconds" => $remaining_seconds ];
        } catch(\Exception $ex1){
            $pdo_connection->rollBack();
            
            $message = implode(" ", [
                "SessionModel :: pingSession",
                $ex1->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            $yes_no = 'Y';
            return [ 'status' => 'OK', 'yes_no_status' => $yes_no, "remaining_seconds" => 0 ];
        }
    }
}
