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

	public static function _new($table_name, Array $fields){
		self::$field = $fields;
        self::$table_name = $table_name;

	}
	public static function _unique(Array $field){

        self::$uniq = 'CONSTRAINT '.getNamerandom().'_uniq UNIQUE ('.implode(',', $field).')';

    }
	public static function _primaryKey($field){
        $sgdb = '';
        if(defined('DATABASE')){
            $database = unserialize(DATABASE);
            $sgdb = $database['ENGINE'];
        }
        switch ($sgdb){
            case 'pgsql':
                $pk = " PRIMARY KEY ($field) ";
                break;
            case 'mysql':
                $pk = " PRIMARY KEY ($field) ";
                break;
            case 'sqlite':
                $pk = " PRIMARY KEY ($field) ";
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
        self::$pk = $pk;

    }

    /**
     * @param $table_origin Modelo local
     * @param $local_field Columna del model local
     * @param $table_reference Nombre del model foraneo
     * @param $field_reference Campo del modelo foraneo
     */
    public static function _ForeignKey($table_origin, $local_field, $table_reference, $field_reference,
                                       $on_update = "NO ACTION", $on_delete = "NO ACTION"){
        $fk = "
            ALTER TABLE $table_origin
            ADD CONSTRAINT fk_".getNamerandom()." FOREIGN KEY ($local_field)
            REFERENCES $table_reference($field_reference) MATCH SIMPLE
            ON UPDATE $on_update
            ON DELETE $on_delete
        ";
        $db = new ConexionDataBase();
        try{
            $db->exec($fk);
            echo 'alter table ' . $table_origin . ' ForeignKey' . PHP_EOL;
        }catch (\PDOException $e){
            echo $fk;
            echo $e->getMessage(). PHP_EOL;
            die();

        }
    }
    public static function _create(aModels $tableModel){
        $create = 'CREATE TABLE '.self::$table_name.' ( ';
        /* coloca los campos en el create table */
        foreach (self::$field  as $key => $field){
            $create .= $field. ',';
            if(preg_match('/NOT NULL/', $field))
                $required = true;
            else
                $required = false;

            $extract_field = explode(' ', $field);
            $tableModel::setFields($extract_field[0], $required);

         }


        $create .= self::$pk . ',';

        /* coloca los constraint */
        if(!empty(self::$uniq))
            $create .= self::$uniq . ',';


        $create = trim($create,',') . ')';

        $db = new ConexionDataBase();
        try{
            $db->exec($create);
            echo 'table ' . self::$table_name . ' create finish' . PHP_EOL;
        }catch (\PDOException $e){
            echo $e->getMessage(). PHP_EOL . print_r(self::$field) . PHP_EOL;
            /*if( $e->getCode() == 'HY000')
                print("The table already exists " . self::$table_name . PHP_EOL);*/

        }
        self::$uniq = '';
        self::$pk = '';

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

function getNamerandom(){
    $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //posibles caracteres a usar
    $numerodeletras=4; //numero de letras para generar el texto
    $cadena = ""; //variable para almacenar la cadena generada
    for($i=0;$i<$numerodeletras;$i++){
        $cadena .= substr($caracteres,rand(0,strlen($caracteres)),1); /*Extraemos 1 caracter de los caracteres
			entre el rango 0 a Numero de letras que tiene la cadena */
    }
    return $cadena;
}