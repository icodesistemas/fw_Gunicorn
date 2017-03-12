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
    private function getCondition($conditions){
        $where = array();
        foreach ($conditions as $key => $val){
            $where[] =array($key => $val);
        }
        return $where;
    }
    public function getData($fields, $conditions = '', $limit = '', $groupBy = '', $having = '')
    {
        $Query = "select  $fields
                  from $this->table ";
        if(!empty($conditions) && is_string($conditions)){
            $Query .= "where ".$conditions;
            $data = $this->db->getArray($Query);
        }elseif(is_array($conditions)){
            $where = " where ";
            $val= "";
            foreach ($conditions as $key => $value){
                $where .= " $key = ?,";
                $val .= " $value,";
            }
            $where = trim($where,',');
            $val = trim($val,',');
            $Query .= $where;

            $data = $this->db->getArray($Query, explode(',',$val));
        }
        if(count($data) == 1){
            return $data[0];
        }else{
            return $data;
        }
    }
    public function setAdd(Array $value, $redirect=''){
        try{
            $this->db->qqInsert($this->table, $value);


        }catch (\PDOException $e){
            die($e->getMessage());
        }

    }
    public function setDelete($conditions, $limit = ''){

    }

    public function setUpdate(Array $value, Array $conditions){
        $sql = "UPDATE  $this->table set ";

        /* recorremos los value para crear una consulta preparada */
        $array_value = array();
        foreach ($value as $key => $val){
            $sql .= " $key = ?,";
            $array_value[] = $val;
        }


        /* ahora recorremos $condicion */
        $sql = trim($sql,',') . " WHERE ";
        foreach ($conditions as $key => $val){
            $sql .= " $key = ?,";
            $array_value[] = $val;
        }
        $sql = trim($sql,',');

        try{
            $this->DB()->exec($sql, $array_value);

        }catch (\PDOException $e){
            die($e->getMessage());
        }
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