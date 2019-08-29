<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

class ArrayHelper {
    
    public static function changeArrayKeyLower($inputArray){
        $inputArray = array_change_key_case($inputArray,CASE_LOWER);
        $newArr = $inputArray;
        foreach ($inputArray as $k => $v){
            if (is_array($v)){
                $newArr[$k] = self::changeArrayKeyLower($v);
            }
        }
        return $newArr;
    }
}