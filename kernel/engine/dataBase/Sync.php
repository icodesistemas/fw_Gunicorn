<?php
namespace fw_Gunicorn\kernel\engine\dataBase\Sync;

use fw_Gunicorn\kernel\engine\dataBase\CreateTable;

include BASE_DIR . '/fw_Gunicorn/kernel/engine/dataBase/TypeFields.php';

$path_folder_models = '';
$namespaces = '';
function getClassModel(){
    global $path_folder_models, $namespaces;

    $dh = opendir($path_folder_models);

    /* ejecuta el CREATE TABLE */
    while(($file = readdir($dh)) !== false){
        if(is_file($path_folder_models . '/' . $file)){
            $reader = fopen($path_folder_models . '/' . $file, 'r');

            while(!feof($reader)) {
                $linea = fgets($reader);
                if(preg_match("/class /", $linea)){
                    $var = str_replace(' extends aModels {','',$linea);
                    $var = str_replace(' extends aModels{','',$var);

                    $var = str_replace(' implements iMigrate {','',$linea);
                    $var = str_replace(' implements iMigrate{','',$var);

                    $var = str_replace('class ','',$var);

                    $var = trim(str_replace('extends aModels','',$var));

                    $class = $namespaces . $var;
                    $obj = new $class;
                    $obj->__init__();
                    CreateTable::_create($obj);
                    //$obj->__foreignKey();

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
                    $var = str_replace(' extends aModels {','',$linea);
                    $var = str_replace(' extends aModels{','',$var);

                    $var = str_replace(' implements iMigrate {','',$linea);
                    $var = str_replace(' implements iMigrate{','',$var);

                    $var = str_replace('class ','',$var);

                    $var = trim(str_replace('extends aModels','',$var));

                    $class = $namespaces . $var;
                    $obj = new $class;
                    $obj->__foreignKey();

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