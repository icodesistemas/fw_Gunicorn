<?php
/**
 * Created by PhpStorm.
 * User: abejarano
 * Date: 21/03/17
 * Time: 03:55 PM
 */

namespace fw_Gunicorn\kernel\engine\dataBase;


class Constrainst
{
    static function ForeignKey($model_reference, $field_reference,
                               $on_delete = "NO ACTION", $on_update = "NO ACTION"){
        $fk = "
            ALTER TABLE {origin}
            ADD CONSTRAINT fk_".getNamerandom()." FOREIGN KEY {field_origin}
            REFERENCES $model_reference($field_reference) MATCH SIMPLE
            ON UPDATE $on_update
            ON DELETE $on_delete
        ";
        return $fk;
    }

    static function on_update($cascade = false){
        if(!$cascade)
            return 'NO ACTION';
        else
            return 'CASCADE';
    }
    static function on_delete($cascade = false){
        if(!$cascade)
            return 'RESTRICT';
        else
            return 'CASCADE';
    }
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