<?php

namespace Api\Helpers;
use Api\Models\Datasources\AuthDatasource;
use Exception;

class AuthHelper {

    public static function insertTokenDetails($data){
        $ds = new AuthDatasource();
        return $ds->insertToken($data);
    }

}