#!/usr/bin/php
<?php
namespace fw_Gunicorn;

$base_dir = __DIR__ . '/../';
spl_autoload_register(function ($nombre_clase) {
    $file = BASE_DIR . $nombre_clase . '.php';
    $file =str_replace('\\','/', $file);
    if(file_exists($file))
        include $file;
    
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

        require 'kernel/engine/projects/CreateProject.php';
        new \fw_Gunicorn\kernel\engine\projects\CreateProject($argv['2'], $base_dir);
        break;

    case 'startapp':
        /* Detects if a name for project specified */
        if(!isset($argv['2'])){
            exit('please specify name for your application');
        }
        require 'kernel/engine/projects/CreateApplications.php';
        new \fw_Gunicorn\kernel\engine\projects\CreateApplications($argv['2'], $base_dir);
        break;

    case 'sync':
        /* Detects the default applications to be synchronized  */
        if(!isset($argv['2'])){
            exit('please specify name for your application for synchronized');
        }
        $app = $argv['2'];
        /* determie project directory */

        $ruta = __DIR__ . '/../';

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
        require $ruta . $dir_project.'/settings.php';

        /* synchronized the requested application */


        $array_apps = unserialize(APP_INSTALL);
        foreach ($array_apps as $value){
            $application = ".".$app;
            if(preg_match("/$application/", $value)){

                $path_file = str_replace('.', '/', $value);

                require 'kernel/engine/dataBase/Sync.php';
                \fw_Gunicorn\kernel\engine\dataBase\Sync\SyncApplications($path_file);
            }
        }
//        require
}
//var_dump($argv);