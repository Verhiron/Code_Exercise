<?php

namespace Api\Endpoints;
use Api\Helpers\AuthHelper;
use Exception;
use Firebase\JWT\JWT;

class Auth {

    protected $key = "kjsadhaskujhdjkas";

    public function auth(){
        try{
            $issueDate = time();
            $expiration = $issueDate + 3600;
            $payload = array(
                "iss" => "http://localhost/movie-api",
                "aud" => "http://localhost/movie-filter",
                "iat" => $issueDate,
                "nbf" => $issueDate, // Set nbf to the same as iat or an earlier time if needed
                "exp" => $expiration,
                "role" => "admin"
            );

            $jwtToken = JWT::encode($payload, $this->key, "HS256");

            date_default_timezone_set('Europe/London');
            $created_at = new \DateTime();
            $date = new \DateTime("@$expiration");
            $formattedDate = $date->format('Y-m-d H:i:s');
            $formattedCreatedAt = $created_at->format('Y-m-d H:i:s');

            $data = [
                "token"=>$jwtToken,
                "created_at"=>$formattedCreatedAt,
                "expires_at"=>$formattedDate
            ];

            AuthHelper::insertTokenDetails($data);

            return [
                "token"=>$jwtToken,
                "expires"=>$expiration,
            ];

        }catch(Exception $e) {
            return $e->getMessage();
        }
    }


}