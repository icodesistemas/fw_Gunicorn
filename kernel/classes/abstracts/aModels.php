<?php
namespace fw_Gunicorn\kernel\classes\abstracts;

use fw_Gunicorn\kernel\classes\interfaces\iModels;
use fw_Gunicorn\kernel\engine\dataBase\ConexionDataBase;
use fw_Gunicorn\kernel\engine\dataBase\DataBase;

abstract class aModels extends DataBase {
    private $structModel;
    private $fields = array();
    private $model;
    private $uniq;
    private $pk;
    private $fk;


    abstract protected function __fields__();
    abstract protected function __setPrimary();
    abstract protected function __setUnique();
    abstract protected function __foreignKey();

    public function __construct($model_name)
    {
        $this->setTable($model_name);

        $this->structModel = $this->__fields__();
        $this->uniq = $this->__setUnique();
        $this->pk = $this->__setPrimary();
        $this->fk = $this->__foreignKey();

        $this->extractFields();
        parent::__construct();
    }

    /**
     * Extrae los campos de la estructura del model, y los coloca en el array fields
     */
    private function extractFields(){
        foreach ($this->structModel as $key => $value){
            $this->fields[] = trim($key);
        }
    }
    private function setTable($table){
        $this->model = $table;
    }

    /**
     * Crea un la clausula where
     * @param array $conditions son las condiciones que debe cumplir el query para poder ejecutar una sentencia SQL
     * @return $this
     */
    public function where(Array $conditions){
        $this->where = $this->renderWhere($conditions);

        return $this;
    }

    /**
     * Verifica que los campos involucrados en la condicion existen en el modelo
     * @param $conditions array de condiciones que se colocan en la clausula where del query
     */
    private function renderWhere($conditions){

        foreach ($conditions as $key => $value){

            $condition = explode('=', $value);
            $field_condition = $condition[0];

            if(!in_array($field_condition, $this->fields))
                die("Sorry, the field <strong>$field_condition</strong> not exists in the model <strong>$this->model</strong> ");

        }
    }

    protected function __getFieldsModel(){
        return $this->fields;
    }
    protected function __getNameModel(){
        return $this->model;
    }
}