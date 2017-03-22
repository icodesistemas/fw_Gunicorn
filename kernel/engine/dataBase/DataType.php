<?php
/**
 * User: abejarano
 * Date: 21/03/17
 * Time: 03:02 PM
 */

namespace fw_Gunicorn\kernel\engine\dataBase;


class DataType
{
    static function FieldString($max_length, $notNull, $default = ''){
        $sgdb = '';
        $campo = '';
        $type = '';
        if(defined('DATABASE')){
            $database = unserialize(DATABASE);
            $sgdb = $database['ENGINE'];
        }
        switch ($sgdb){
            case 'pgsql':
                $campo .= " character varying ($max_length) ";
                break;
            case 'mysql':
                $campo .= " varchar ($max_length)";
                break;
            case 'sqlite':
                $campo .= ' text';
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
        if ($notNull)
            $campo .= " NOT NULL ";

        if(!empty($default))
            $campo .= " DEFAULT '$default' ";


        return $campo;
    }
    static function FieldText($notNull, $default = ''){
        $campo =  ' text';
        if ($notNull)
            $campo .= " NOT NULL ";

        if(!empty($default))
            $campo .= " DEFAULT '$default' ";

        return $campo;
    }
    static function FieldInteger($notNull, $default = ''){
        $sgdb = '';
        if(defined('DATABASE')){
            $database = unserialize(DATABASE);
            $sgdb = $database['ENGINE'];
        }

        $campo = '';
        switch ($sgdb){
            case 'pgsql':
                $campo .= ' BIGINT';
                break;
            case 'mysql':
                $campo .= ' BIGINT';
                break;
            case 'sqlite':
                $campo .= ' INTEGER';
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
        if ($notNull)
            $campo .= " NOT NULL ";

        if($default >=0 || $default <=0)
            $campo .= " DEFAULT $default ";


        return $campo;
    }

    static function FieldFloat($max_digits, $decimal_places, $notNull, $default = ''){
        $sgdb = '';
        if(defined('DATABASE')){
            $database = unserialize(DATABASE);
            $sgdb = $database['ENGINE'];
        }
        $campo = '';
        switch ($sgdb){
            case 'pgsql':
                $campo .= " NUMERIC($max_digits, $decimal_places)";
                break;
            case 'mysql':
                $campo .= " DECIMAL($max_digits, $decimal_places)";
                break;
            case 'sqlite':
                $campo .= " REAL";
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
        if ($notNull)
            $campo .= " NOT NULL ";

        if(!empty($default))
            $campo .= " DEFAULT $default ";

        return $campo;
    }
    static function FieldBoolean($notNull, $default = ''){
        $sgdb = '';
        if(defined('DATABASE')){
            $database = unserialize(DATABASE);
            $sgdb = $database['ENGINE'];
        }
        $campo = '';
        switch ($sgdb){
            case 'pgsql':
                $type = ' boolean';
                break;
            case 'mysql':
                $type = ' bool';
                break;
            case 'sqlite':
                $type = ' INTEGER';
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
        if ($notNull)
            $campo .= " NOT NULL ";

        if(!empty($default))
            $campo .= " DEFAULT $default ";


        return $campo;
    }
    static function FieldChar($notNull, $default = ''){
        $sgdb = '';
        if(defined('DATABASE')){
            $database = unserialize(DATABASE);
            $sgdb = $database['ENGINE'];
        }
        $campo = '';
        switch ($sgdb){
            case 'pgsql':
                $campo .= ' "char"';
                break;
            case 'mysql':
                $campo .= ' "char"';
                break;
            case 'sqlite':
                $campo .= ' text';
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
        if ($notNull)
            $campo .= " NOT NULL ";

        if(!empty($default))
            $campo .= " DEFAULT '$default' ";

        return $campo;
    }
    static function FieldDate($notNull, $default = ''){
        $campo = ' date ';

        if ($notNull)
            $campo .= " NOT NULL ";

        if(!empty($default))
            $campo .= " DEFAULT $default ";

        return $campo;
    }
    static function FieldDateTime($notNull, $default = ''){
        $sgdb = '';
        if(defined('DATABASE')){
            $database = unserialize(DATABASE);
            $sgdb = $database['ENGINE'];
        }
        $campo = '';
        switch ($sgdb){
            case 'pgsql':
                $campo .= ' timestamp without time zone';
                break;
            case 'mysql':
                $campo .= ' datetime';
                break;
            case 'sqlite':
                $campo .= ' datetime';
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }
        if ($notNull)
            $campo .= " NOT NULL ";

        if(!empty($default))
            $campo .= " DEFAULT $default ";


        return $campo;
    }
    static function FieldAutoField(){
        $sgdb = '';
        $campo = '';

        if(defined('DATABASE')){
            $database = unserialize(DATABASE);
            $sgdb = $database['ENGINE'];
        }
        switch ($sgdb){
            case 'pgsql':
                $campo .= ' serial';
                break;
            case 'mysql':
                $campo .= ' BIGINT NOT NULL AUTO_INCREMENT ';
                break;
            case 'sqlite':
                $campo .= ' AUTO INCREMENT';
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }


        return $campo;
    }
    static function DateTimeNow(){
        if(defined('DATABASE')){
            $database = unserialize(DATABASE);
            $sgdb = $database['ENGINE'];
        }
        switch ($sgdb){
            case 'pgsql':
                return ' now() ';
                break;
            case 'mysql':
                return " current_timestamp ";
                break;
            case 'sqlite':
                //$campo .= ' AUTO INCREMENT';
                break;
            default:
                die('Error, database engine is not defined');
                break;
        }


    }
}