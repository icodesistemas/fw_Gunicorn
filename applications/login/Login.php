<?php
namespace fw_Gunicorn\applications\login;
use fw_Gunicorn\applications\login\models\User;
use fw_Gunicorn\kernel\engine\middleware\Session;


class Login extends Session {
    private $model;
    public function __construct()
    {
        $this->model = new User();
    }
    public function makeLogout(){
        $id_session = Session::getDecrypt($_COOKIE['sessionid']);
        $this->destroy();

        $sql = 'delete from fw_gunicorn_session where session_id = '.$id_session.' ';
        $this->model->DB()->exec($sql);

    }
    public function makeLogin($user, $pass)
    {
        $data = $this->model->getData(
            'nom_user, email_user,login_user, pass_user', " login_user = '$user' "
        );
        if(empty($data))
            return 'The user is not registered';


        if($data['pass_user'] != Login::getEncryptPass($pass))
            return 'Invalid password';

        return $this->registerSession($data);
    }
    /**
     * @param $data Datos del usuario que esta ingresando al sistema
     */
    private function registerSession($data){
        if(defined('SESSION_COOKIE_AGE'))
            $expire_sesion = time()+ intval(SESSION_COOKIE_AGE);
        else
            $expire_sesion = time()+ 60*24;

        $id_session = $this->setCookies('sessionid', $this->getGenerateSecretKey(), $expire_sesion);

        $fullname = $this->getEncrypt($data['nom_user']);
        $email = $this->getEncrypt($data['email_user']);
        $username = $this->getEncrypt($data['login_user']);

        /* register session db */

        $data = array(
            'session_id' => $id_session,
            'session_data' => serialize([$fullname, $email, $username]),
            'expire_date' => date('Y-m-d H:i:s',$expire_sesion)
        );
        $this->model->DB()->qqInsert('fw_gunicorn_session', $data);
        return true;
    }
    /**
     * @param $pass This is the password you need to encrypt
     */
    public static function getEncryptPass($pass){
        $semilla = "ZDQ3ZmRmZTM1MjIxODk0MWUxNDRlMGQ4YmMzZTBlZjI=";
        return hash('sha256',md5(sha1($pass). sha1($semilla)));
    }
}