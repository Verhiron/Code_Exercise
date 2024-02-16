<?php

namespace Api\Endpoints;

use Api\Helpers\MovieHelper;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;
use Exception;

class Movies{

    protected $key = "kjsadhaskujhdjkas";

    public function fetch(){
        return MovieHelper::fetchAll();
    }

    public function movie(){

        try{

            $headers = getallheaders();

            if(isset($headers['Authorization'])){
                $jwt = trim(str_replace('Bearer', '', $headers['Authorization']));

                $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
            }else{
                throw new Exception("INVALID_AUTHORIZATION");
            }

            if ($decoded->role === 'admin') {

                if(isset($_GET["title"])){
                    $title = $_GET["title"];
                    return MovieHelper::SearchMovie($title);
                }

                $params = [];

                $genre = isset($_GET['genre']) ? $_GET['genre'] : null;
                $year = isset($_GET['year']) ? $_GET['year'] : null;
                $rating = isset($_GET['rating']) ? $_GET['rating'] : null;

                if($genre || $year || $rating) {
                    if ($genre) {
                        $params["genre"] = $genre;
                    }
                    if ($year) {
                        $params["year"] = $year;
                    }
                    if ($rating) {
                        $params["rating"] = $rating;
                    }

                    return MovieHelper::FilterMovies($params);
                }else{
                    return $this->fetch();
                }

            }else{
                throw new Exception("INVALID_TOKEN", 401);
            }

        }catch(\PDOException $e){
            return $e->getMessage();
        }




    }

    public function genres(){
        return MovieHelper::FetchGenres();
    }

    public function test(){

        try {
//            $_SERVER['HTTP_AUTHORIZATION'] = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L01vdmllLWFwaSIsImF1ZCI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJpYXQiOjE3MDgwNDk0MjksIm5iZiI6MTcwODA0OTQyOSwiZXhwIjoxNzA4MDUzMDI5LCJ1c2VybmFtZSI6ImphbWVzIn0.xgJGezHZmvqq35eDf_7CxJbU-YWpQ4TulgNNoRv28VU";

            $headers = getallheaders();
            $jwt = trim(str_replace('Bearer', '', $headers['Authorization']));

            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));

            if ($decoded->role === 'admin') {
                return "spongebob";
            } else {
                throw new Exception("FAILED", 404);
            }
        } catch (\Exception $e) {
            // Handle JWT decoding errors here
            return $e->getMessage();
        }
    }

}
