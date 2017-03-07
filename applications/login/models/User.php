<?php

namespace apps\Admin\models;


use fw_Gunicorn\kernel\classes\abstracts\aModels;
use fw_Gunicorn\kernel\engine\dataBase\CreateTable;
use fw_Gunicorn\kernel\engine\dataBase\TypeFields;


class User extends aModels {
    public function __construct(){
        parent::__construct('fw_gunicorn_user');
    }

    public function __init__(){
        CreateTable::_new('admin_user',[
            TypeFields\FieldString('nom_user', 80, false),
            TypeFields\FieldString('login_user', 80, true),
            TypeFields\FieldString('email_user', 120, true),
            TypeFields\FieldString('pass_user', 80, true),
            TypeFields\FieldDateTime('date_Created', false, TypeFields\DateTimeNow() ),
            TypeFields\FieldAutoField('id')
        ]);
        CreateTable::_primaryKey('id');
    }
}