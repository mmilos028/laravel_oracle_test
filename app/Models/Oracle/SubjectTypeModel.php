<?php
namespace App\Models\Oracle;

use App\Helpers\ErrorHelper;

use Illuminate\Support\Facades\DB;

class SubjectTypeModel
{
    /**
     * returns subject type, from managment types returns name-type of subject
     * @param $subject_name
     * @return string
     */
    public static function getSubjectType($subject_name)
    {
                
        $connection = DB::connection('oracle');
        $pdo_connection = $connection->getPdo();
        
        try{
                        
            $statement_string = 'BEGIN :p := DYNVAR.VAL(:var_in); END;';
            $stmt = $pdo_connection->prepare($statement_string);
            
            $stmt->bindParam(':var_in', $subject_name);
            
            $value_out = "";
            $stmt->bindParam(':p', $value_out, \PDO::PARAM_STR, 255);
            
            $stmt->execute();
           
            //$connection->disconnect();
                       
            return ['status' => "OK", 'value' => $value_out];
            
        }catch(\PDOException $ex1){
            $pdo_connection->rollBack();
            //$connection->disconnect();
            
            $message = implode(" ", [
                "SubjectTypesModel :: getSubjectType",
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
                "SubjectTypesModel :: getSubjectType",
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