<?php

namespace fw_Gunicorn\kernel\classes\abstracts;



class aDefinitionModels{
    private $table_name;
    private $fields = array();
    private $sgdb = 'sqlite';
    private $primaryKey = '';

    public function __construct()
    {
        if(define('DATABASE')){
            $database = unserialize(DATABASE);
            $this->setSgdb($database['ENGINE']);
        }

    }

    private function setSgdb(){
        $this->sgdb;
    }
    public function sync()
    {
        // TODO: Implement sync() method.
    }

    public function setTableName($table)
    {
        $this->table_name = $table;
    }

    public function setPrimaryKey($pk = null)
    {
        // TODO: Implement setPrimaryKey() method.
    }

    public function FieldString($name_field,$max_length, $null = true, $default = ''){
        $type = '';
        switch ($this->sgdb){
            case 'pgsql':
                $type = 'character varying';
                break;
            case 'mysql':
                $type = 'varchar';
                break;
            case 'sqlite':
                $type = 'text';
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
    }
    public function FieldText($name_field, $null = true, $default = ''){
        $type = 'text';

    }
    public function FieldInteger($name_field, $null = true, $default = ''){
        $type = '';
        switch ($this->sgdb){
            case 'pgsql':
                $type = 'bigint';
                break;
            case 'mysql':
                $type = 'bigint';
                break;
            case 'sqlite':
                $type = 'integer';
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
    }
    public function FieldFloat($name_field, $max_digits, $decimal_places, $null = true, $default = ''){
        $type = '';
        switch ($this->sgdb){
            case 'pgsql':
                $type = 'bigint';
                break;
            case 'mysql':
                $type = 'bigint';
                break;
            case 'sqlite':
                $type = 'integer';
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
    }
    public function FieldBoolean($name_field, $null = true, $default = ''){
        $type = '';
        switch ($this->sgdb){
            case 'pgsql':
                $type = 'boolean';
                break;
            case 'mysql':
                $type = 'bool';
                break;
            case 'sqlite':
                $type = 'integer';
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
    }
    public function FieldChar($name_field, $null = true, $default = ''){
        $type = '';
        switch ($this->sgdb){
            case 'pgsql':
                return 'char';
                break;
            case 'mysql':
                return 'char';
                break;
            case 'sqlite':
                return 'text';
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
    }
    public function FieldDate($name_field, $null = true, $default = ''){
        $type = 'date';
    }
    public function FieldDateTime($name_field, $null = true, $default = ''){
        $type = '';
        switch ($this->sgdb){
            case 'pgsql':
                $type = 'timestamp without time zone';
                break;
            case 'mysql':
                $type = 'datetime';
                break;
            case 'sqlite':
                $type = 'datetime';
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
    }
}