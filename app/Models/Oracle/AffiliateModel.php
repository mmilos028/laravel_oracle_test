<?php
namespace App\Models\Oracle;

use App\Helpers\ErrorHelper;
use App\Helpers\ArrayHelper;

use Illuminate\Support\Facades\DB;

class AffiliateModel
{
    
    public static function checkParentAffiliateBannedStatus($username)
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try {
            $statement_string = 'CALL MANAGMENT_CORE.M$CHECK_AFF_FOR_AFF(:p_user_name_in, :y_n_out)';
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':p_user_name_in', $username);
            $banned_status = 'Y';
            $stmt->bindParam(':y_n_out', $banned_status, \PDO::PARAM_STR, 10);
            $stmt->execute();
            
            //$connection->disconnect();
            
            return [ 'status' => 'OK', 'banned_status' => $banned_status ];
        }catch(\PDOException $ex1){
            $pdo_connection->rollBack();
            //$connection->disconnect();
            
            $message = implode(" ", [
                "AffiliateModel :: checkParentAffiliateBannedStatus",
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
                "AffiliateModel :: checkParentAffiliateBannedStatus",
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
    
    public static function getAffiliateDetailsLastLogin($session_id, $affiliate_id)
    {
        
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try {
            $statement_string = "CALL MANAGMENT_CORE.M\$SUBJECT_DETAIL_LAST_LOGIN(:p_session_id_in, :p_subject_id_in, :p_subject_detail_out)";
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':p_session_id_in', $session_id, \PDO::PARAM_STR);
            $stmt->bindParam(':p_subject_id_in', $affiliate_id, \PDO::PARAM_STR);
            
            $subject_detail_cursor = "";
            $stmt->bindParam(':p_subject_detail_out', $subject_detail_cursor, \PDO::PARAM_STMT);
            
            $stmt->execute();
            
            oci_execute($subject_detail_cursor, OCI_DEFAULT);
            $subject_detail_array = [];
            oci_fetch_all($subject_detail_cursor, $subject_detail_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($subject_detail_cursor);
            
            $subject_detail_array = ArrayHelper::changeArrayKeyLower($subject_detail_array);
            
            return [ 'status' => 'OK', 'subject_detail' => $subject_detail_array ];
        }catch(\PDOException $ex1){
            $pdo_connection->rollBack();
            //$connection->disconnect();
            
            $message = implode(" ", [
                "AffiliateModel :: getAffiliateDetailsLastLogin",
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
                "AffiliateModel :: getAffiliateDetailsLastLogin",
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
    
    public static function getAffiliateDetails($session_id, $affiliate_id)
    {
        
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try {
            $statement_string = "CALL MANAGMENT_CORE.M\$SUBJECT_DETAIL(:p_session_id_in, :p_subject_id_in, :p_subject_detail_out)";
            $stmt = $pdo_connection->prepare($statement_string);
                        
            $stmt->bindParam(':p_session_id_in', $session_id, \PDO::PARAM_STR);
            $stmt->bindParam(':p_subject_id_in', $affiliate_id, \PDO::PARAM_STR);
            
            $subject_detail_cursor = "";
            $stmt->bindParam(':p_subject_detail_out', $subject_detail_cursor, \PDO::PARAM_STMT);
            
            $stmt->execute();
            
            oci_execute($subject_detail_cursor, OCI_DEFAULT);
            $subject_detail_array = [];
            oci_fetch_all($subject_detail_cursor, $subject_detail_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($subject_detail_cursor);
                        
            $subject_detail_array = ArrayHelper::changeArrayKeyLower($subject_detail_array);
            
            return [ 'status' => 'OK', 'subject_detail' => $subject_detail_array ];
        }catch(\Exception $ex){
            $pdo_connection->rollBack();
            
            $message = implode(" ", [
                "AffiliateModel :: getAffiliateDetails",
                $ex->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_type" => "Exception",
                "exception_message" => $ex->getTraceAsString()
            ];
        }
    }
    
    public static function getAllRoles($session_id, $sub_role = null){
        
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try{
            $statement_string = "CALL MANAGMENT_CORE.M\$LIST_ALL_ROLES(:p_session_id_in, :p_subroles_in, :p_sub_roles_out)";
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':p_session_id_in', $session_id, \PDO::PARAM_STR);
            $stmt->bindParam(':p_subroles_in', $sub_role, \PDO::PARAM_STR);
            //p_sub_roles to return all roles is null or equals 'Ad / Collector' | 'Ad / Cashier'
            $subroles_cursor = "";
            $stmt->bindParam(':p_sub_roles_out', $subroles_cursor, \PDO::PARAM_STMT);
                        
            $stmt->execute();
            
            oci_execute($subroles_cursor, OCI_DEFAULT);
            $subroles_array = [];
            oci_fetch_all($subroles_cursor, $subroles_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($subroles_cursor);
            
            $subroles_array = ArrayHelper::changeArrayKeyLower($subroles_array);
            
            return [ "status" => "OK", "list_subroles" => $subroles_array ];
        }catch(\Exception $ex){
            
            $pdo_connection->rollBack();
            
            $message = implode(" ", [
                "AffiliateModel :: getAllRoles",
                $ex->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_type" => "Exception",
                "exception_message" => $ex->getTraceAsString()
            ];
        }
    }
    
    public static function getAffiliatesForNewUserForm($session_id)
    {
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try {
            
            $statement_string = "CALL MANAGMENT_CORE.M\$SELECT_SUBJECTS(:p_session_id_in, :list_subjects_out)";
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':p_session_id_in', $session_id, \PDO::PARAM_STR);
            
            $list_subjects_cursor = "";
            $stmt->bindParam(':list_subjects_out', $list_subjects_cursor, \PDO::PARAM_STMT);
            
            $stmt->execute();
            
            oci_execute($list_subjects_cursor, OCI_DEFAULT);
            $list_subjects_array = [];
            oci_fetch_all($list_subjects_cursor, $list_subjects_array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
            oci_free_cursor($list_subjects_cursor);
            
            $list_subjects_array = ArrayHelper::changeArrayKeyLower($list_subjects_array);
            
            return [ 'status' => 'OK', 'report' => $list_subjects_array ];
            
        } catch(\Exception $ex){
            
            $pdo_connection->rollBack();
            
            $message = implode(" ", [
                "AffiliateModel :: getAffiliatesForNewUserForm",
                $ex->getMessage()
            ]);
            
            ErrorHelper::writeError($message, $message);
            
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_type" => "Exception",
                "exception_message" => $ex->getTraceAsString()
            ];
        }
    }
    
    
}