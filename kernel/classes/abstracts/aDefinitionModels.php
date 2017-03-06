<?php

namespace fw_Gunicorn\kernel\classes\abstracts;


class aDefinitionModels{
    private $table_name;
    private $fields = array();
    private $sgdb = 'sqlite';
    private $primaryKey = '';

    public function __construct()
    {

    }

    public function sync()
    {
        // TODO: Implement sync() method.
    }

    public function setTableName($table)
    {
        $this->table_name = $table;
    }

    public function setPrimaryKey($pk = null)
    {
        // TODO: Implement setPrimaryKey() method.
    }

}