<?php
/**
 * Define el tipo de dato con respecto a manejador de base de datos que se esta usando en la programacion de la
 * aplicacion.
 */
namespace fw_Gunicorn\kernel\engine\dataBase\TypeFields;

function FieldString($name_field, $max_length, $notNull, $default = ''){
    $sgdb = '';
    $campo = $name_field;
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
            $campo .= ' varchar ($max_length)';
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

function FieldText($name_field, $notNull, $default = ''){
    $campo = $name_field . ' text';
    if ($notNull)
        $campo .= " NOT NULL ";

    if(!empty($default))
        $campo .= " DEFAULT '$default' ";

    return $campo;
}
function FieldInteger($name_field, $notNull, $default = ''){
    $sgdb = '';
    if(defined('DATABASE')){
        $database = unserialize(DATABASE);
        $sgdb = $database['ENGINE'];
    }

    $campo = $name_field;
    switch ($sgdb){
        case 'pgsql':
            $campo .= ' bigint';
            break;
        case 'mysql':
            $campo .= ' bigint';
            break;
        case 'sqlite':
            $campo .= ' integer';
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

function FieldFloat($name_field, $max_digits, $decimal_places, $notNull, $default = ''){
    $sgdb = '';
    if(defined('DATABASE')){
        $database = unserialize(DATABASE);
        $sgdb = $database['ENGINE'];
    }
    $campo = $name_field;
    switch ($sgdb){
        case 'pgsql':
            $campo .= " numeric($max_digits, $decimal_places)";
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
function FieldBoolean($name_field, $notNull, $default = ''){
    $sgdb = '';
    if(defined('DATABASE')){
        $database = unserialize(DATABASE);
        $sgdb = $database['ENGINE'];
    }
    $campo = $name_field;
    switch ($sgdb){
        case 'pgsql':
            $type = ' boolean';
            break;
        case 'mysql':
            $type = ' bool';
            break;
        case 'sqlite':
            $type = ' integer';
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
function FieldChar($name_field, $notNull, $default = ''){
    $sgdb = '';
    if(defined('DATABASE')){
        $database = unserialize(DATABASE);
        $sgdb = $database['ENGINE'];
    }
    $campo = $name_field;
    switch ($sgdb){
        case 'pgsql':
            $campo .= ' "char"';
            break;
        case 'mysql':
            $campo .= ' char';
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
function FieldDate($name_field, $notNull, $default = ''){
    $campo = $name_field.' date ';

    if ($notNull)
        $campo .= " NOT NULL ";

    if(!empty($default))
        $campo .= " DEFAULT $default ";

    return $campo;
}
function FieldDateTime($name_field, $notNull, $default = ''){
    $sgdb = '';
    if(defined('DATABASE')){
        $database = unserialize(DATABASE);
        $sgdb = $database['ENGINE'];
    }
    $campo = $name_field;
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
        $campo .= " DEFAULT '$default' ";


    return $campo;
}
function FieldAutoField($name_field){
    $sgdb = '';
    $campo = $name_field;

    if(defined('DATABASE')){
        $database = unserialize(DATABASE);
        $sgdb = $database['ENGINE'];
    }
    switch ($sgdb){
        case 'pgsql':
            $campo .= ' serial';
            break;
        case 'mysql':
            $campo .= ' AUTO_INCREMENT';
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
function DateTimeNow(){
    return 'now()';
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