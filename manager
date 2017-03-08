#!/usr/bin/php
<?php
namespace fw_Gunicorn;

use fw_Gunicorn\applications\login\Login;
use fw_Gunicorn\applications\login\Session;
use fw_Gunicorn\kernel\engine\dataBase\ConexionDataBase;

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
        break;
    case 'createsuperuser':

        $isMail = false;
        while(!$isMail){
            echo "Email: ";
            $email = trim(fgets(STDIN));
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $isMail = true;
            }
        }

        $isNull = true;
        while($isNull){
            echo "Username: ";
            $username = trim(fgets(STDIN));

            if(!empty(trim($username))){
                $isNull = false;
            }
        }

        $len = 0;
        while($len < 8){
            echo "Passsword: ";
            $pass = trim(fgets(STDIN));
            $len = strlen(trim($pass));
        }

        /* include file settings for project */
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
        try{
            $db = new ConexionDataBase();
            $data = array(
                'login_user' => $username,
                'email_user' => $email,
                'pass_user' => Login::getEncryptPass($pass)
            );
            $db->qqInsert('fw_gunicorn_user', $data);
            echo 'User created successfully' . PHP_EOL;
        }catch (\PDOException $e){
            die($e->getMessage());
        }

}
