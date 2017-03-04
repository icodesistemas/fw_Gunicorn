<?php
namespace fw_Gunicorn\kernel;
spl_autoload_register(function ($nombre_clase) {

    $file = BASE_DIR . str_replace('\\','/',$nombre_clase)  . '.php';
    if(file_exists($file)){
        include $file;
    }

});
/* include vendor composer */

require 'vendor/autoload.php';

new fw_iCode\engine\middleware\Request();

/* Set cookies for the session */
$session = new \fw_iCode\engine\middleware\Session();
$session->setCookies('csrftoken', SECRET_KEY);
//$session->destroy();


