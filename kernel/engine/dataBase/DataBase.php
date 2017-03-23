<?php

/**
 *   14/02/2014
 *   autor: Angel Bejarano
 *   Driver para conexiones de base de datos mysql y postgres
 */
namespace fw_Gunicorn\kernel\engine\dataBase;

use fw_Gunicorn\kernel\classes\abstracts\aModels;


use PDO;

abstract class DataBase extends PDO
{
    protected $_where = "";
    protected $_join = array();
    protected $_field_join = array();

    abstract protected function __getNameModel();
    abstract protected function __getFieldsModel();

    private function setConexion(){
        $params = unserialize(DATABASE);

        $driver = $params['ENGINE'];

        if ($driver == 'pgsql' || $driver == 'mysql') {
            $db = $params['NAME'];
            $host = $params['HOST'];
            $port = $params['PORT'];
            $user = $params['USER'];
            $pass = $params['PASSWORD'];

            $dsn = "$driver:dbname=$db;host=$host;port=$port";
            if ($driver == 'pgsql') {
                $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_PERSISTENT => false
                );
            } else {
                $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_PERSISTENT => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                );
            }
        } elseif ($driver == 'sqlite') {
            $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => false
            );
            $db = $params['ROUTE_DB'];
            $dsn = "sqlite:$db";
            $user = null;
            $pass = null;
        }
        try {
            parent::__construct($dsn, $user, $pass, $options);
            restore_exception_handler();

            if (!empty($params['SHEMA'])) {
                $this->setSchema($params['SHEMA'], $driver);
            }
            $this->driver = $driver;
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    private function setSchema($schema, $driver)
    {
        switch ($driver) {
            case 'mysql':
                //$stmt->query('use '.$schema);
                break;
            case 'pgsql':
                $stmt = parent::prepare('set search_path to ' . $schema);
                $stmt->execute();
                break;
            default:
                # code...
                break;
        }
    }
    public function run(){
        echo '<pre>';
        print_r($this->_join);
        print_r($this->_where);
    }

    private function getSelectiveFields(Array $fields){
        $select = "";
        foreach ($fields as $model => $value) {
            /* creo un array de campos para el modelo que indica la variable $model */
            $array_field = explode(',', $value);
            foreach ($array_field as $field) {
                $var = strtolower(trim($model)) . "." . trim($field);
                $this->checkFieldExistsModel($var);
                $select .= $var . ",";
            }
        }
        return trim($select,',');
    }
    /**
     * Crea una consulta a la db
     * @param string $field campos que retornara la consulta.
     * @return array Arrray con el resultado de la consulta.
     */
    public function find($field = ""){
        $SELECT = "SELECT ";
        if(!empty($field) && !is_array($field))
            $this->checkFieldExistsModel($field);

        elseif (is_array($field)) 
            $field = $this->getSelectiveFields($field);

        else
            $field = $this->__getFieldsModel();

        # si e mayor a cero entonces la consulta es un inner join
        if(count($this->_join) > 0)
            $SELECT .= $field . $this->getInnerJoin() .' '. $this->_where;
            
        else
            $SELECT .= $field . ' FROM ' . $this->__getNameModel() .' '. $this->_where;


        echo $SELECT;

        #$this->setConexion();

        /*
        $stmt = parent::prepare($SELECT);
        $stmt->execute();*/
        return array();
        #echo $this->__getNameModel();

    }
    private function getInnerJoin(){
        $inner = "";
        foreach ($this->_join as $key => $value) {
            $inner .= " ".$value;
        }
        return $inner;
    }
    private function checkFieldExistsModel($field){
        $array_field = explode(',', $field);
        foreach ($array_field as $value){
            aModels::findFieldModel($value);
        }
        return true;
    }
}