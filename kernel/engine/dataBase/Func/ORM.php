<?php
/**
 * Created by PhpStorm.
 * User: abejarano
 * Date: 21/03/17
 * Time: 10:19 PM
 */

namespace fw_Gunicorn\kernel\engine\dataBase\Func;


class ORM
{
    static function _OR($params1, $params2){
        return $params1 . ' or '. $params2;
    }
    static function _IN($field, Array $values){
        return $field . " in implode(',', $values)  ";
    }
}