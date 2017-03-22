<?php
namespace fw_Gunicorn\kernel\classes\abstracts;

use fw_Gunicorn\kernel\classes\interfaces\iModels;
use fw_Gunicorn\kernel\engine\dataBase\ConexionDataBase;
use fw_Gunicorn\kernel\engine\dataBase\DataBase;

abstract class aModels extends DataBase {
    private $structModel;
    private static $fields = array();
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
     * Extrae los campos de la estructura del model, y los coloca en el array fields siendo el nombre del campo
     * la llave y el valor el tipo de dato
     */
    private function extractFields(){
        foreach ($this->structModel as $key => $value){
            /* obtiene el tipo de dato, simplicandolos a solo numericos y de cadena */

            if(preg_match('/BIGINT/', $value) ||
                preg_match('/INTEGER/', $value) ||
                preg_match('/NUMERIC/', $value) ||
                preg_match('/REAL/', $value) ||
                preg_match('/REAL/', $value) ||
                preg_match('/serial/', $value) ||
                preg_match('/AUTO INCREMENT/', $value) ||
                preg_match('/bool/', $value) ||
                preg_match('/boolean/', $value) ||
                preg_match('/DECIMAL/', $value)
            ){

                $tipo_dato = 'NUMERIC';
            }
            if(preg_match('/char/', $value) ||
                preg_match('/text/', $value) ||
                preg_match('/datetime/', $value) ||
                preg_match('/timestamp without time zone/', $value) ||
                preg_match('/date/', $value) ||
                preg_match('/character varying/', $value) ||
                preg_match('/varchar/', $value)
            ){
                $tipo_dato = 'STRING';
            }
            aModels::$fields[] = [trim($key) => $tipo_dato];
            #$this->fields[] = trim($key);
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
        $this->_where = $this->renderWhere($conditions);

        return $this;
    }

    /**
     * Verifica que los campos involucrados en la condicion existen en el modelo
     * @param $conditions array de condiciones que se colocan en la clausula where del query
     * @return Retorna el where listo para ser usado por el query
     */
    private function renderWhere($conditions){
        $where = " where ";
        foreach ($conditions as $key => $value){

            if(preg_match('/=/', $value)){
                $condition = explode('=', $value);
                $field_condition = trim($condition[0]);
            }


            /* si tiene un or, in, not in elimina el signo de llaves */
            if(preg_match('/{or}/', $value))
                $value =  str_replace('{or}', 'OR', $value);
            else if(preg_match('/{in}/', $value))
                $value =  str_replace('{in}', 'IN', $value);
            else if(preg_match('/{not in}/', $value))
                $value =  str_replace('{in}', 'NOT IN', $value);
            else
                aModels::findFieldModel($field_condition);

            $where .= " $value and ";
        }
        $where = trim($where,' and ');

        if ($where == " where ")
            return "";
        else
            return $where;

    }

    /**
     * busca si un campo pasado por pasametro es realmente campo del model
     * @param $name_field nombre del campo que se desea buscar
     * @return bool True si el campo pertenece al modelo y False y no pertenece
     */
    static function findFieldModel($name_field){
        foreach (aModels::$fields as $value){
            foreach ($value as $field => $type){
                if($name_field == $field){
                    return $type;
                }
            }
        }
        throw new \Exception("The field $name_field does not exist in the model ");
    }

    /**
     * @return string Retorna los campos separados por coma (,)
     */
    protected function __getFieldsModel(){
        $fields = "";
        foreach (aModels::$fields as $value){
            foreach ($value as $field => $type){
                $fields .= $field.  ', ';
            }
        }

        return trim($fields, ', ');
    }
    protected function __getNameModel(){
        return $this->model;
    }
}