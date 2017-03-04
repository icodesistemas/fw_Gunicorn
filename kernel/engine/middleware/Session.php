<?php

namespace fw_Gunicorn\kernel\engine\middleware;

class Session{
    public function hola(){
        //echo 'clase session';
    }
    public function setCookies($id, $value){
        if(!isset($_COOKIE[$id]))
            if(defined('SESSION_COOKIE_AGE'))
                setcookie($id, md5($this->getGenerateSecretKey($value)),time()+ intval(SESSION_COOKIE_AGE));
            else
                setcookie($id, md5($this->getGenerateSecretKey($value)),time()+ 60*24);

    }
    private function getGenerateSecretKey(){
        $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $numerodeletras=10;
        $semilla = "ZDQ3ZmRmZTM1MjIxODk0MWUxNDRlMGQ4YmMzZTBlZjI=";
        $cadena = ""; //variable para almacenar la cadena generada
        for($i=0;$i<$numerodeletras;$i++){
            $cadena .= substr($caracteres,rand(0,strlen($caracteres)),1); /*Extraemos 1 caracter de los caracteres
            entre el rango 0 a Numero de letras que tiene la cadena */
        }
        return hash('sha256',md5(sha1($cadena). sha1($semilla)));

    }
    /**
     * @return bool
     * Returns true if there is an active session, Otherwise it will return false
     */
    public function isActiveSession(){
        if(!isset($_COOKIE['csftoken'])){
            return false;
        }else{
            return true;
        }
    }
    public function destroy(){
        foreach ($_COOKIE as $key => $value){
            unset($_COOKIE[$key]);
            setcookie($key, '');
        }

    }
}