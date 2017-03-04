<?php
namespace fw_Gunicorn\kernel\engine\applications\login;

use fw_Gunicorn\kernel\classes\abstracts\aDefinitionModels;
use fw_Gunicorn\kernel\classes\abstracts\aModels;

$model = new aDefinitionModels();
$model->setTableName('fw_icode_session');
$model->FieldInteger('session_id',false);
$model->FieldText('session_data',false);
$model->FieldDateTime('expire_date',false);

class Session extends aModels{
    public function __construct()
    {
        $table = 'fw_icode_session';
        parent::__construct($table);
        $this->setTable($table);
    }
}