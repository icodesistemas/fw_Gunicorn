<?php
namespace fw_Gunicorn\kernel\classes\abstracts;

use fw_Gunicorn\kernel\classes\interfaces\iModels;
use fw_Gunicorn\kernel\engine\dataBase\ConexionDataBase;

class aModels implements iModels {
    private $db;
    private $table;
    public function __construct($table)
    {
        if(!defined('DATABASE'))
            die('Please submit the login credentials to the database');

        $this->db = new ConexionDataBase();
        $this->setTable($table);
    }
    private function setTable($table){
        $this->table = $table;
    }
    public function getData($field, $conditions, $limit = '', $groupBy = '', $having = '')
    {
        // TODO: Implement getData() method.
    }

    public function setDelete($conditions, $limit = '')
    {
        // TODO: Implement setDelete() method.
    }

    public function setUpdate($conditions)
    {
        // TODO: Implement setUpdate() method.
    }

    public function setExecQuery($sql)
    {
        // TODO: Implement setExecQuery() method.
    }

    public function getLastInsertId()
    {
        // TODO: Implement getLastInsertId() method.
    }

    public function getAffectedRows()
    {
        // TODO: Implement getAffectedRows() method.
    }
}