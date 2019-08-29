<?php

namespace App\Models\Oracle;

use Illuminate\Support\Facades\DB;

class TestModel
{

    private static $DEBUG = false;
    
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
            
            //$pdo_connection->commit();
            
            //$connection->disconnect();
            
            return [
                "status" => "OK",
                "result" => $list_countries_array
            ];
        }catch(\PDOException $ex1){
            $connection->rollBack();
            $message = implode(" ", [
                $ex1->getMessage()
            ]);
            /*ErrorHelper::writeError($message, $message);*/
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
            /*ErrorHelper::writeError($message, $message);*/
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex2->getMessage()
            ];
        }
    }
    
    public static function listCountriesPdo($backoffice_session_id){
        
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
            
            array_change_key_case($list_countries_array, CASE_LOWER);
            
            $pdo_connection->commit();
            
            //$connection->disconnect();
                     
            return [
                "status" => "OK",
                "result" => $list_countries_array
            ];
        }catch(\PDOException $ex1){
            $connection->rollBack();
            $message = implode(" ", [
                $ex1->getMessage()
            ]);
            /*ErrorHelper::writeError($message, $message);*/
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
            /*ErrorHelper::writeError($message, $message);*/
            return [
                "status" => "NOK",
                "message" => $message,
                "exception_message" => $ex2->getMessage()
            ];
        }
    }

    /**
     * @return array
     */
    public static function test(){
        try{
         
            DB::connection('oracle')->beginTransaction();
            $statement_string = "SELECT TO_CHAR (SYSDATE, 'MM-DD-YYYY HH24:MI:SS') \"NOW\" FROM DUAL";
            $fn_result = DB::connection('oracle')->select(
                $statement_string
                /*[
                    "p_session_id_in" => $backoffice_session_id
                ]*/
            );

            //$cursor_name = $fn_result[0]->cur_result_out;
            //$cursor_result = DB::connection('oracle')->select("fetch all in {$cursor_name};");

            DB::connection('oracle')->commit();

            return [
                "status" => "OK",
                "result" => $fn_result
            ];
        }catch(\PDOException $ex1){
            DB::connection('oracle')->rollBack();
            $message = implode(" ", [                
                $ex1->getMessage()
            ]);
            /*ErrorHelper::writeError($message, $message);*/
            return [
                "status" => "NOK",
                "message" => $message
            ];
        }catch(\Exception $ex2){
            DB::connection('oracle')->rollBack();
            $message = implode(" ", [
                $ex2->getMessage()
            ]);
            /*ErrorHelper::writeError($message, $message);*/
            return [
                "status" => "NOK",
                "message" => $message
            ];
        }
    }

}
