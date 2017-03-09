<?php

namespace fw_Gunicorn\kernel\engine\middleware;

class Session{

    public function setCookies($id, $value, $expire_session = null){
        if(isset($_COOKIE[$id]))
            return;


        if(empty($expire_session)){
            $expire_sesion = time()+ 60*24;
            if(defined('SESSION_COOKIE_AGE'))
                $expire_sesion = time()+ intval(SESSION_COOKIE_AGE);
        }

        $value = md5($this->getGenerateSecretKey($value));

        setcookie($id, $value, $expire_sesion);
        return $this->getEncrypt($value);
    }
    public static function getDecrypt($clave){
        $semilla = "4e15cb955dbfb0e4180f97e936be3419";
        return str_rot13(str_replace(($semilla), "", base64_decode(str_rot13(base64_decode($clave)))));

    }
    public function getEncrypt($clave){
        $semilla = "4e15cb955dbfb0e4180f97e936be3419";
        return base64_encode(str_rot13(base64_encode(str_rot13($clave).($semilla))));

    }
    public function getGenerateSecretKey($cadena = ""){
        $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $numerodeletras=10;
        $semilla = "ZDQ3ZmRmZTM1MjIxODk0MWUxNDRlMGQ4YmMzZTBlZjI=";
        $cadena = md5($cadena); //variable para almacenar la cadena generada

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