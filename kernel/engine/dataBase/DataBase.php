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

    abstract protected function __getNameModel();
    abstract protected function __getFieldsModel();

    public function __construct()
    {

    }
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

    /**
     * Crea una consulta a la db
     * @param string $field campos que retornara la consulta.
     * @return array Arrray con el resultado de la consulta.
     */
    public function find($field = ""){
        $SELECT = "select ";
        if(!empty($field))
            $this->checkFieldExistsModel($field);
        else
            $field = $this->__getFieldsModel();

        $SELECT .= $field . ' from ' . $this->__getNameModel() .' '. $this->_where;

        echo $SELECT;
        #$this->setConexion();

        /*
        $stmt = parent::prepare($SELECT);
        $stmt->execute();*/
        return array();
        #echo $this->__getNameModel();

    }
    private function checkFieldExistsModel($field){
        $array_field = explode(',', $field);
        foreach ($array_field as $value){
            aModels::findFieldModel($value);
        }
        return true;
    }
}