<?php

namespace fw_Gunicorn\applications\login\models;

//include BASE_DIR . '/fw_Gunicorn/kernel/engine/dataBase/TypeFields.php';
use fw_Gunicorn\kernel\classes\abstracts\aModels;
use fw_Gunicorn\kernel\classes\interfaces\iMigrate;
use fw_Gunicorn\kernel\engine\dataBase\CreateTable;
use fw_Gunicorn\kernel\engine\dataBase\DataType;
use fw_Gunicorn\kernel\engine\dataBase\TypeFields;


class Session extends aModels{

    /**
     * Session constructor.
     */
    public function __construct(){
        parent::__construct('fw_gunicorn_user');
    }

    protected function __fields__()
    {
        $field = [
            'session_id' => DataType::FieldString(200, true),
            'session_data' => DataType::FieldText(true),
            'expire_date' => DataType::FieldDateTime(true),
            'status' => DataType::FieldChar(true, 'A')
        ];
        return $field;
    }

    protected function __setPrimary()
    {
        $pk = ['session_id'];
        return $pk;
    }

    protected function __setUnique()
    {
        // TODO: Implement __setUnique() method.
    }

    protected function __foreignKey()
    {
        // TODO: Implement __foreignKey() method.
    }
}