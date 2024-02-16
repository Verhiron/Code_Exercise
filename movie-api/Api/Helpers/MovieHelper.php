<?php

namespace Api\Helpers;
use Api\Models\Datasources\MoviesDatasource;
use Exception;

class MovieHelper {

    public static function fetchAll(){
        $ds = new MoviesDatasource();
        return $ds->fetch();
    }

    public static function SearchMovie($title){
        $ds = new MoviesDatasource();
        return $ds->SearchMovie($title);
    }

    public static function FilterMovies($params){
        $ds = new MoviesDatasource();
        return $ds->FilterMovies($params);
    }

    public static function FetchGenres(){
        $ds = new MoviesDatasource();
        return $ds->FetchGenres();
    }

}