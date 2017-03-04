<?php

namespace fw_Gunicorn\kernel\engine\applications\login\models;

use fw_Gunicorn\kernel\classes\abstracts\aDefinitionModels;
use fw_Gunicorn\kernel\classes\abstracts\aModels;

$model = new aDefinitionModels();
$model->setTableName('fw_icode_user');
$model->setPrimaryKey('id');
$model->FieldString('nom_user', 80, false);
$model->FieldString('login_user', 80, false);
$model->FieldString('pass_user', 80, false);
$model->FieldDateTime('date_Created', false, date('Y-m-d H:i:s'));
$model->FieldChar('status', false, 'A');

class User extends aModels {
    public function __construct()
    {
        $table = 'fw_icode_user';
        parent::__construct();
        $this->setTable($table);
    }
}
