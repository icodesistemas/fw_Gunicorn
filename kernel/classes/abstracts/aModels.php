<?php
namespace fw_Gunicorn\kernel\classes\abstracts;

use fw_Gunicorn\kernel\classes\interfaces\iModels;
use fw_Gunicorn\kernel\engine\dataBase\ConexionDataBase;

class aModels implements iModels {
    private $db;
    private $table;
    private static  $fields = array();
    public function __construct($table)
    {
        if(!defined('DATABASE'))
            die('Please submit the login credentials to the database');

        $this->db = new ConexionDataBase();
        $this->setTable($table);
    }
    public function DB(){
        return $this->db;
    }
    private function setTable($table){
        $this->table = $table;
    }
    public function getData($fields, $conditions = '', $limit = '', $groupBy = '', $having = '')
    {
        $Query = "select  $fields
                  from $this->table ";
        if(!empty($conditions))
            $Query .= "where $conditions";

        $data = $this->db->getArray($Query);

        if(count($data) == 1){
            return $data[0];
        }else{
            return $data;
        }
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

    public static function setFields($field, $required){
        self::$fields[] = array($field => $required);
    }

}