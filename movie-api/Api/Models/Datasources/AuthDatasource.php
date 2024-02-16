<?php

namespace Api\Models\Datasources;

use Api\Models\Databases\DatabaseFoundation;

class AuthDatasource extends DatabaseFoundation{

    protected $db;

    //establishes what database we want to access
    protected $dbName = 'movie';

    protected $tblName = 'tokens';

    public function __construct(){
        $this->db = new DatabaseFoundation($this->dbName, $this->tblName);
    }

    public function insertToken($data){
        return $this->db->insert($data);
    }

}
