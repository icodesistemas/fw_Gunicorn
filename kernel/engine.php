<?php
namespace fw_Gunicorn\kernel;
use fw_Gunicorn\kernel\engine\middleware\Request;

spl_autoload_register(function ($nombre_clase) {
	$file = BASE_DIR . str_replace('\\','/',$nombre_clase)  . '.php';
    if(file_exists($file)){
        include $file;
    }

});
/* include vendor composer */

require BASE_DIR . '/fw_Gunicorn/vendor/autoload.php';



new \fw_Gunicorn\kernel\engine\middleware\Request();

/* Set cookies for the session */
$session = new \fw_Gunicorn\kernel\engine\middleware\Session();
$session->setCookies('csrftoken', SECRET_KEY);
//$session->destroy();


