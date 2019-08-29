<?php
namespace App\Helpers;

class JWTHelper 
{

    public static function generateTokenWithPayloadString($payload_json_string, $secret_key = 'abC123!')
    {
        // Create token header as a JSON string
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);        
       
        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        
        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload_json_string));
        
        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret_key, true);
        
        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        
        return array("status" => "OK", "jwt_token" => $jwt);
    }
    
    public static function generateTokenWithPayload($payload, $secret_key = 'abC123!')
    {
        // Create token header as a JSON string
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        
        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        
        $payload_json_string = json_encode($payload);
        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload_json_string));
        
        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret_key, true);
        
        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        
        return array("status" => "OK", "jwt_token" => $jwt);
    }
    
    public static function validateToken($jwt_token, $secret_key = 'abC123!')
    {
        $jwt_token_parts = explode('.', $jwt_token);
        
        $base64UrlHeader = str_replace(['-', '_', ''], ['+', '/', '='], $jwt_token_parts[0]);
        $base64UrlPayload = str_replace(['-', '_', ''], ['+', '/', '='], $jwt_token_parts[1]);
        $base64UrlSignature = str_replace(['-', '_', ''], ['+', '/', '='], $jwt_token_parts[2]);
        
        $header = base64_decode($base64UrlHeader);
        $payload_json_string = base64_decode($base64UrlPayload);
        $signature = base64_decode($base64UrlSignature);
        
        $test_signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret_key, true);
        
        if($signature === $test_signature){
            return array("status" => "OK", "payload" => $payload_json_string);
        }
        else{
            return array("status" => "NOK");
        }
    }
}