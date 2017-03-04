<?php

namespace fw_Gunicorn\kernel\classes\interfaces;

use fw_Gunicorn\kernel\engine\dataBase\ConexionDataBase;

interface iModels{

    public function getData($field, $conditions, $limit = '', $groupBy = '', $having = '');
    public function setDelete($conditions);
    public function setUpdate($conditions);
    public function setExecQuery($sql);
    public function getLastInsertId();
    public function getAffectedRows();
    //public function setAddField($name_file, $type, $long, $forenkey = array());
}