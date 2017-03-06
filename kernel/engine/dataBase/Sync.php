<?php
namespace fw_Gunicorn\kernel\engine\dataBase\Sync;

use fw_Gunicorn\kernel\engine\dataBase\CreateTable;

$path_folder_models = '';
$namespaces = '';
function getClassModel(){
    global $path_folder_models, $namespaces;

    $dh = opendir($path_folder_models);
    $dir_project = '';
    while(($file = readdir($dh)) !== false){
        $reader = fopen($path_folder_models . '/' . $file, 'r');
        while(!feof($reader)) {
            $linea = fgets($reader);

            if(preg_match("/class /", $linea)){
                $var = str_replace(' extends aModels {','',$linea);
                $var = str_replace(' extends aModels{','',$var);
                $var = trim(str_replace('class ','',$var));

                $class = $namespaces . $var;
                $obj = new $class;
                $obj->__init__();
                CreateTable::_create($obj);

            }

        }
        fclose($reader);
    }


}
function SyncApplications($path){
    global $path_folder_models, $namespaces;

    $namespaces = str_replace('/','\\', $path) . '\\' . 'models\\';
    $path_folder_models = BASE_DIR . $path.'/models';
    getClassModel();
}