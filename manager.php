#!/usr/bin/php
<?php

$base_dir = __DIR__ . '/';
spl_autoload_register(function ($nombre_clase) {

    if(file_exists($nombre_clase . '.php'))
        include $nombre_clase . '.php';
});
/* Detects if a command specified */
if (count($argv) == 1){
    exit('please specify a command');
}

$command = $argv['1'];

switch ($command){
    case 'startproject':

        /* Detects if a name for project specified */
        if(!isset($argv['2'])){
            exit('please specify name for your project');
        }

        require 'fw_iCode/engine/projects/CreateProject.php';
        new \fw_iCode\engine\projects\CreateProject($argv['2'], $base_dir);
        break;

    case 'startapp':
        /* Detects if a name for project specified */
        if(!isset($argv['2'])){
            exit('please specify name for your application');
        }
        require 'fw_iCode/engine/projects/CreateApplications.php';
        new \fw_iCode\engine\projects\CreateApplications($argv['2'], $base_dir);
        break;

    case 'sync':
        /* Detects the default applications to be synchronized  */
        if(!isset($argv['2'])){
            exit('please specify name for your application for synchronized');
        }
        $app = $argv['2'];
        /* determie project directory */

        $ruta = __DIR__ . 'manager.php/';
        $dh = opendir($ruta);
        $dir_project = '';
        while(($file = readdir($dh)) !== false){
            if(preg_match("/Project/i", $file)){
                $dir_project = $file;
            }
        }
        if(empty($dir_project)){
            die('Unexpected error, unable to determine project directory');
        }
        require $dir_project.'/settings.php';

        /* synchronized the requested application */
        $array_apps = unserialize(APP_INSTALL);
        foreach ($array_apps as $value){
            $application = ".".$app;

            if(preg_match("/$application/", $value)){

                $namespace_app = str_replace('.', '\\', $value);
                $instance = new $namespace_app();
                $instance->sync();
            }
        }
//        require
}
//var_dump($argv);