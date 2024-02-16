<?php

namespace Api\Models\Databases;
use PDO;
use PDOException;

class DatabaseFoundation {

    private $conn = null;

    protected $dbName = null;

    protected $tblName = null;

    public function __construct($dbName, $tblName){
        try{
            //determines the name of the db
            $this->dbName = $dbName;
            $this->tblName = $tblName;

            //establishes the db connection
            return $this->conn = new PDO('mysql:dbname='. $this->dbName .';host=127.0.0.1', 'root', '');

        }catch (PDOException $e){
            return $e->getMessage();
        }
    }

    public function prepare($query = null, $data = null){

        try{

            $stmt = "SELECT * FROM " . $this->tblName. " " . $query;

            $request = $this->conn->prepare($stmt);

            if (is_array($data)) {
                $this->bindSQLData($request, $data);
            }

            $request->execute();

            $result = $request->fetchAll(PDO::FETCH_ASSOC);

            if(is_null($result)){
                throw new PDOException("ERROR");
            }

//            return json_encode($result);
            return $result;

        }catch(PDOException $e){
            $e->getMessage();
        }

    }

    public function customQuery($stmt = null){

        try{

            if($stmt === null){
                throw new PDOException("UNDEFINED_STATEMENT");
            }

            $request = $this->conn->prepare($stmt);


            if(!$request->execute()){
                throw new PDOException("ERROR_EXECUTING");
            }

            return $request->fetchAll(PDO::FETCH_ASSOC);


        }catch(PDOException $e){
            return $e->getMessage();
        }


    }

    public function insert($data){
        try{

            $insert = [];

            foreach(array_keys($data) as $key){
                $insert[] = ":" . $key;
            }

            $stmt = "INSERT INTO " . $this->tblName . " (" . implode(",",array_keys($data)) . ") VALUES (" . implode(",",$insert) . ")";

            $request = $this->conn->prepare($stmt);

            $request->execute($data);

            return $this->conn->lastInsertId();

        }catch(PDOException $e){
            $e->getMessage();
        }
    }



    public function bindSQLData($request, $data){
        foreach ($data as $var => $value) {
            if (is_array($value)) {
                $request->bindValue($var, $value[0], $value[1]);
            } else {
                $request->bindValue($var, $value);
            }
        }
    }
}
