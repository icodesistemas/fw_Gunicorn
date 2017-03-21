<?php

/**
 * @author abejarano
 * @version 0.1 
 * Session, es la clase se encarga de gestionar el registro de sessiones en el sistema basado en un back end de
 * base de datos.
 */

namespace fw_Gunicorn\kernel\engine\middleware;

use fw_Gunicorn\kernel\engine\dataBase\ConexionDataBase;

class Session{

    /**
     * @param  Array data contiene todo el contenido que se desea guardar en la session
     * @param  $DB 
     * @return [type]
     */
    public function registerSession(Array $data, $DB){
        if(defined('SESSION_COOKIE_AGE'))
            $expire_sesion = time()+ intval(SESSION_COOKIE_AGE);
        else
            $expire_sesion = time()+ 60*24;

        $id_session = $this->setSession('sessionid', $this->getGenerateSecretKey());

        $fullname = $this->getEncrypt($data['nom_user']);
        $email = $this->getEncrypt($data['email_user']);
        $username = $this->getEncrypt($data['login_user']);

        /* register session db */

        $data = array(
            'session_id' => $id_session,
            'session_data' => serialize([$fullname, $email, $username]),
            'expire_date' => date('Y-m-d H:i:s',$expire_sesion)
        );
        try{
            $DB->qqInsert('fw_gunicorn_session', $data);
            return true;
        }catch(\PDOException $e){
            return false;
        }
        
        
    }

    /**
     * @param $id clave de la session
     * @param $value valor de la clave     
     */
    public function setSession($id, $value){
        if(isset($_SESSION[$id]))
            return;

        $value = md5($this->getGenerateSecretKey($value));

        $_SESSION[$id] = $value;
        return $value;
    }
    /**
     * @param  $clave es la frase que se desea descifrar
     * @return una frase descifrada
     */
    public static function getDecrypt($clave){
        $semilla = "4e15cb955dbfb0e4180f97e936be3419";
        return str_rot13(str_replace(($semilla), "", base64_decode(str_rot13(base64_decode($clave)))));

    }
    /**
     * @param  $clave es la frase que se desea cifrar
     * @return una frase cifrada que permite posteriormente ser descifrada
     */
    public function getEncrypt($clave){
        $semilla = "4e15cb955dbfb0e4180f97e936be3419";
        return base64_encode(str_rot13(base64_encode(str_rot13($clave).($semilla))));

    }
    /**
     * Genera llaves secretas usadas para los parametros de session de php.
     * @param  $cadena es un string que por default está vacio si no está vacio será la cadena que se cifrará
     * @return una llave cifrada.
     */
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
     * @param  $url_paramets es la url que será varificada que si tiene un sesion iniciada entonces la permitirá.
     * @return retorna la url en cuetion para su posterior utilización.
     */
    public static function loginRequired($url_paramets){

        /* captura la url invocada en el browser */
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
        if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
        $uri = trim($uri, '/');

        /* verifica que la url que se invoco en el browaser es la que se esta definiendo como requerida que tenga incio
        de sesion */
        if($uri != trim($url_paramets,'/')){
            return $url_paramets;
        }
        /* verifica que la constante LOGIN_URL haya sido definida en el archivo settings */
        if(!defined('LOGIN_URL')){
            die('Sorry for the settings file you should define the constant LOGIN_URL');
        }
        $redirec = trim(DOMAIN_NAME,'/') .'/'. trim(LOGIN_URL,'/');

        /* si la cookie sessionid no existe entonces no existe el inicio de sesion */

        if(!isset($_SESSION['sessionid'])){
            header("Location: $redirec ");

        }
       
        return $url_paramets;
    }
    /**
     * Returns true if there is an active session, Otherwise it will return false
     */
    public function checkSessionActive(){
        if(!isset($_SESSION['sessionid'])){
            return;
        }
        $sql = 'select expire_date from fw_gunicorn_session where session_id = ?';
        $db = new ConexionDataBase();
        $expire = $db->getValue($sql, array($_SESSION['sessionid']));
        $date_current = date('Y-m-d H:i:s');

        /* borro las sessiones caducadas */
        $sql = "delete from fw_gunicorn_session where expire_date < '".$date_current."' ";
        $db->exec($sql);

        if($expire < $date_current){
            $this->destroy();
            $redirec = trim(DOMAIN_NAME,'/') .'/'. trim(LOGIN_URL,'/');
            header("Location: $redirec ");
        }
        $this->renovateSession($db);
    }
    /**
     * renovateSession refrezca la fecha hora minuto y segundo en que caducará la session
     * @param  $db conexion a la base de datos     
     */
    private function renovateSession($db){
        if(defined('SESSION_COOKIE_AGE'))
            $expire_sesion = time()+ intval(SESSION_COOKIE_AGE);
        else
            $expire_sesion = time()+ 60*24;

        $expire = date('Y-m-d H:i:s', $expire_sesion);
        $sql = "update fw_gunicorn_session set expire_date = '".$expire."' where session_id = '".$_SESSION['sessionid']."' ";
        $db->exec($sql);

    }
    public function destroy(){
        foreach ($_SESSION as $key => $value){
            if($key != 'csrftoken'){
                unset($_SESSION[$key]);
            }
        }
    }
}