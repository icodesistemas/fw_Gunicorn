<?php
namespace fw_Gunicorn\kernel\engine\dataBase;

class CreateTable{
    /**
     * @param $table_name Nombre de la tabla
     * @param array $field array de campos que tendra la tabla
     */
    private static $field = array();
    private static $pk = '';
    private static $uniq = '';
    private static $fk = '';
    private static $table_name = '';
	public static function _new($table_name, Array $fields){
		self::$field = $fields;
        self::$table_name = $table_name;

	}
	public static function _primaryKey($field){
        self::$pk = $field;

    }
    public static function _unique($field){
        self::$uniq = $field;

    }
    public static function _ForeignKey($field, $table_name){
        self::$fk = $field;
    }
    public static function _create(){
        print_r(self::$field);

        $create = 'CREATE TABLE '.self::$table_name.' ( ';
        foreach (self::$field  as $key => $field){
                $create .= $field. ',';

         }
        echo trim($create,',').' )';
        die();

    }

}
