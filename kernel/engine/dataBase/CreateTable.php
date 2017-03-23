<?php
namespace fw_Gunicorn\kernel\engine\dataBase;

use fw_Gunicorn\kernel\classes\abstracts\aModels;

class CreateTable{
    /**
     * @param $table_name Nombre de la tabla
     * @param array $field array de campos que tendra la tabla
     */
    private static $field = array();
    private static $pk = '';
    private static $uniq = '';
    private static $table_name = '';

    private static function _unique(Array $field){

        return 'CONSTRAINT '.getNamerandom().'_uniq UNIQUE ('.implode(',', $field).')';

    }
	private static function _primaryKey($field){
        $sgdb = '';
        if(defined('DATABASE')){
            $database = unserialize(DATABASE);
            $sgdb = $database['ENGINE'];
        }
        switch ($sgdb){
            case 'pgsql':
                $pk = " PRIMARY KEY ($field), ";
                break;
            case 'mysql':
                $pk = " PRIMARY KEY ($field), ";
                break;
            case 'sqlite':
                $pk = " PRIMARY KEY ($field), ";
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
        return $pk;

    }

    /**
     * @param $table_origin Modelo local
     * @param $local_field Columna del model local
     * @param $table_reference Nombre del model foraneo
     * @param $field_reference Campo del modelo foraneo
     */
    public static function _foreignKey(aModels $tableModel){
        $array_fk = $tableModel->__foreignKey();
        if(count($array_fk) ==0)
            return;

        $nameModel = $tableModel->__getNameModel();

        foreach ($array_fk as $field => $fk){
            $var_fk = str_replace('{origin}', $nameModel,$fk);
            $sql_fk = str_replace('{field_origin}', $field, $var_fk);
            try{
                $sql_fk =explode(";", $sql_fk);

                #excuete create index
                $tableModel->raw($sql_fk[0]);

                #excuete create forenkey
                $tableModel->raw($sql_fk[1]);
                echo 'ForeigKey ' . $tableModel->__getNameModel() . ' create finish' . PHP_EOL;

            }catch (\PDOException $e){
                echo $e->getMessage() . PHP_EOL;
                echo $sql_fk[0];
                die();
            }

        }
    }
    public static function _create(aModels $tableModel){
        $create = 'CREATE TABLE '.$tableModel->__getNameModel().' ( ';

        /* coloca los campos en el create table */
        foreach ($tableModel->__fields__()  as $field => $data_type){
            $create .= $field . ' ' . $data_type . ',';


         }

        /* verifica si tiene primary key */
        if(count($tableModel->__setPrimary()) > 0){
            foreach ($tableModel->__setPrimary() as $field_pk){
                $create .= CreateTable::_primaryKey($field_pk);
            }
        }else{
            /* sno tiene primary key se le crea uno, primero se agrega el campo */
            $field_pk = $tableModel->__getNameModel() . '_id';
            $create .= $field_pk . ' ' . DataType::FieldInteger(true) . ',';

            /* se crea el pk */
            $create .= CreateTable::_primaryKey($field_pk);
        }

        /* verifica si tiene unique */
        if(count($tableModel->__setUnique()) > 0){
            $create .= CreateTable::_unique($tableModel->__setUnique());
        }

        $create = trim($create,', ') . ')';

        try{
            $tableModel->raw($create);
            echo 'table ' . $tableModel->__getNameModel() . ' create finish' . PHP_EOL;
        }catch (\PDOException $e){
            echo $e->getMessage() . PHP_EOL;
        }

    }

}



function action_update($bool = false){
    if(!$bool)
        return 'NO ACTION';
    else
        return 'CASCADE';
}
function action_delete($bool = false){
    if(!$bool)
        return 'NO ACTION';
    else
        return 'CASCADE';
}

function action_delete_restrict(){
    return 'RESTRICT';
}
