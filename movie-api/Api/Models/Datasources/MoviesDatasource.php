<?php

namespace Api\Models\Datasources;

use Api\Models\Databases\DatabaseFoundation;

class MoviesDatasource extends DatabaseFoundation{

    protected $db;

    //establishes what database we want to access
    protected $dbName = 'movie';

    protected $tblName = 'movies';

    public function __construct(){
        $this->db = new DatabaseFoundation($this->dbName, $this->tblName);
    }

    //the default call will fetch all
    public function fetch(){
        return $this->db->prepare();
    }

    public function SearchMovie($title){
        $query = "WHERE name LIKE :title";
//        $query = "WHERE name := title";
        $data = [
            ":title" => ["%{$title}%", \PDO::PARAM_STR],
        ];

        return $this->db->prepare($query, $data);
    }

    public function FilterMovies($params){
        $query = [];
        $data = [];
        foreach ($params as $key => $value) {
            $query[] = "$key = :$key";

            //checks the datatype of the values
            if(is_int($value)){
                $dataType = \PDO::PARAM_INT;
            }else{
                $dataType = \PDO::PARAM_STR;
            }

            // Add the parameter to the $data array
            $data[":$key"] = [$value, $dataType];
        }

        //build up the where statement with the included params
        $query = "WHERE " . implode(' AND ', $query);

        return $this->db->prepare($query, $data);
    }

    public function FetchGenres(){
        $query = "SELECT DISTINCT genre from movies";
        return $this->db->customQuery($query);
    }




}
