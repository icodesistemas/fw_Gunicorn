<?php

namespace fw_Gunicorn\kernel\engine\middleware;

class Response{
    private static $message = '';
    public static function setMessage($message){
        Response::$message = $message;
    }
    public static  function getMessage(){
        return Response::$message;
    }
    public static function redirect($url){
        $redirec = trim(DOMAIN_NAME,'/') .'/'. trim($url,'/');
        header("Location: $redirec ");
    }
}