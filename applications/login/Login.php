<?php
namespace fw_Gunicorn\applications\login;
use apps\Admin\models\User;
use fw_Gunicorn\kernel\engine\middleware\Session;


class Login extends Session {
    private $model;
    public function __construct()
    {
        $this->model = new User();
    }

    public function makeLogin($user, $pass)
    {
        $data = $this->model->getData(
            'nom_user, email_user,login_user, pass_user', "'login_user = $user "
        );
        if(empty($data))
            throw new Exception('The user is not registered');


        if($data['pass_user'] != Login::getEncryptPass($pass))
            throw new Exception('Invalid password');

        /*  aqui viene a registrar la session */

        $this->setCookies('fullname', $data['nom_user']);
        $this->setCookies('email', $data['email_user']);
        $this->setCookies('username', $data['login_user']);

    }

    /**
     * @param $pass This is the password you need to encrypt
     */
    public static function getEncryptPass($pass){
        $semilla = "ZDQ3ZmRmZTM1MjIxODk0MWUxNDRlMGQ4YmMzZTBlZjI=";
        return hash('sha256',md5(sha1($pass). sha1($semilla)));
    }
}