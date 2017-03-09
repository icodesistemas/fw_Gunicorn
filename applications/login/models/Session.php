<?php

namespace fw_Gunicorn\applications\login\models;

//include BASE_DIR . '/fw_Gunicorn/kernel/engine/dataBase/TypeFields.php';
use fw_Gunicorn\kernel\classes\abstracts\aModels;
use fw_Gunicorn\kernel\classes\interfaces\iMigrate;
use fw_Gunicorn\kernel\engine\dataBase\CreateTable;
use fw_Gunicorn\kernel\engine\dataBase\TypeFields;


class Session extends aModels implements iMigrate {
    public function __construct(){
        parent::__construct('fw_gunicorn_user');
    }

    public function __init__(){
        CreateTable::_new('fw_gunicorn_session',[
            TypeFields\FieldInteger('session_id', true),
            TypeFields\FieldText('session_data',true),
            TypeFields\FieldDateTime('expire_date',true),
            TypeFields\FieldChar('status', true, 1)
        ]);
        CreateTable::_primaryKey('session_id');
        CreateTable::_unique(array(
            'expire_date'
        ));
    }

    public function __foreignKey()
    {
        // TODO: Implement __foreignKey() method.
    }
}