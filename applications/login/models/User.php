<?php

namespace fw_Gunicorn\applications\login\models;


use fw_Gunicorn\kernel\classes\abstracts\aModels;
use fw_Gunicorn\kernel\engine\dataBase\CreateTable;
use fw_Gunicorn\kernel\engine\dataBase\TypeFields;


class User extends aModels {
    public function __construct(){
        parent::__construct('fw_gunicorn_user');
    }

    public function __init__(){
        CreateTable::_new('fw_gunicorn_user',[
            TypeFields\FieldString('nom_user', 80, false),
            TypeFields\FieldString('login_user', 80, false),
            TypeFields\FieldString('pass_user', 80, false),
            TypeFields\FieldDateTime('date_Created', false, false,date('Y-m-d H:i:s')),
            TypeFields\FieldAutoField('id')
        ]);
        CreateTable::_primaryKey('id');

    }
}