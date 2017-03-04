<?php
namespace fw_Gunicorn\kernel\classes\abstracts;

use fw_iCode\classes\interfaces\iModels;
use fw_iCode\engine\dataBase\ConexionDataBase;

class aModels implements iModels {
    private $db;
    private $table;
    public function __construct($table)
    {
        $this->db = new ConexionDataBase();
    }
    public function setTable($table){
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