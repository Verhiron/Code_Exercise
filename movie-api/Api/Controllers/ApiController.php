<?php

namespace Api\Controllers;
use Exception;


class ApiController {

    protected $path;
    protected $target;
    protected $method;
    protected $result = null;

    public function __construct(){

        $uri = $_SERVER['REQUEST_URI'];

        $uri = rtrim($uri,"/");

        $uri = explode('/', $uri);

        //sets default endpoint
        $this->target = isset($uri[2]) ? $uri[2] : "Movies";
        //sets default call
        $params = isset($uri[3]) ? $uri[3] : "fetch";

        $this->target = strtolower($this->target);

        //separates the query and the method on dynamic instance methods
        $query_method = explode('?', $params);
        $this->method = $query_method[0];

        //cors is a pain
        header("Access-Control-Allow-Origin: http://localhost:3000");
        header("Access-Control-Allow-Headers: Authorization, Content-Type");
        header("Content-type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: *");
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
    }

    public function process(){

        try{

          $class = "Api/Endpoints/" .strtolower($this->target);
//            $class = "Api/Endpoints/" . ucfirst(strtolower($this->target));
            $this->path = str_replace('/', '\\', $class);

            if(!class_exists($this->path)){
                throw new \PDOException("REQUEST_NOT_FOUND", 404);
            }

            $instance = new $this->path();

            if(!method_exists($instance, $this->method)){
                throw new \PDOException("REQUEST_NOT_FOUND", 404);
            }

            $this->result = $instance->{$this->method}();

            if(empty($this->result)){
                throw new \PDOException("NO_RESULTS_FOUND", 404);
            }

            return json_encode(array("Success"=>true,"Code"=>200,"Response"=>$this->result), JSON_PRETTY_PRINT);

        }catch(Exception $e){

            return json_encode(array("Success"=>false,"Code"=>$e->getCode(), "Message"=>$e->getMessage(), "Response"=>$this->result), JSON_PRETTY_PRINT);
        }
    }

}