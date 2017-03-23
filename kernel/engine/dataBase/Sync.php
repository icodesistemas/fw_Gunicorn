<?php
namespace fw_Gunicorn\kernel\engine\dataBase\Sync;

use fw_Gunicorn\kernel\engine\dataBase\CreateTable;



$path_folder_models = '';
$namespaces = '';

function getInstancModel($model){
    global $namespaces;
    $var = str_replace(' extends aModels{','',$model);
    $var = str_replace(' extends aModels {','',$var);
    $var = str_replace('class ','',$var);

    $var = trim(str_replace('extends aModels','',$var));

    return $namespaces . $var;
}

function getClassModel(){
    global $path_folder_models;

    $dh = opendir($path_folder_models);

    /* ejecuta el CREATE TABLE */
    while(($file = readdir($dh)) !== false){
        if(is_file($path_folder_models . '/' . $file)){
            $reader = fopen($path_folder_models . '/' . $file, 'r');

            while(!feof($reader)) {
                $linea = fgets($reader);
                if(preg_match("/class /", $linea)){

                    $class_model = getInstancModel($linea);

                    $obj_model = new $class_model;

                    CreateTable::_create($obj_model);

                }

            }
            fclose($reader);
        }
        
    }
    
    /* ejecuta el ALTER TABLE para los foren*/
    $dh2 = opendir($path_folder_models);
    while(($file2 = readdir($dh2)) !== false){
        if(is_file($path_folder_models . '/' . $file2)){
            $reader = fopen($path_folder_models . '/' . $file2, 'r');
            while(!feof($reader)) {
                $linea = fgets($reader);

                if(preg_match("/class /", $linea)){
                    $class_model = getInstancModel($linea);
                    $obj_model = new $class_model;
                    CreateTable::_foreignKey($obj_model);

                }

            }
            fclose($reader);
        }
        
    }



}
function SyncApplications($path){
    global $path_folder_models, $namespaces;

    $namespaces = str_replace('/','\\', $path) . '\\' . 'models\\';
    $path_folder_models = BASE_DIR . $path.'/models';
    getClassModel();
}