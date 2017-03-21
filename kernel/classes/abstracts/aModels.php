<?php
namespace fw_Gunicorn\kernel\classes\abstracts;

use fw_Gunicorn\kernel\classes\interfaces\iModels;
use fw_Gunicorn\kernel\engine\dataBase\ConexionDataBase;

abstract class aModels {
    private $db;
    private $table;

    abstract protected function __fields__();
    abstract protected function __setPrimary();
    abstract protected function __setUnique();
    abstract protected function __foreignKey();

    public function __construct($model_name)
    {
        /*if(!defined('DATABASE'))
            die('Please submit the login credentials to the database');

        $this->db = new ConexionDataBase();*/
        $this->setTable($model_name);

        $this->model = $this->__fields__();
        $this->uniq = $this->__setUnique();
        $this->pk = $this->__setPrimary();
        $this->fk = $this->__foreignKey();

    }

    private function setTable($table){
        $this->table = $table;
    }

}