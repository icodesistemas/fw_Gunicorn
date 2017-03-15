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
        $id_session = $_SESSION['sessionid'];
        $this->destroy();

        $sql = "delete from fw_gunicorn_session where session_id = '".$id_session."' ";
        $this->model->DB()->exec($sql);

        $redirec = trim(DOMAIN_NAME,'/') .'/'. trim(LOGIN_URL,'/');
        header("Location: $redirec ");

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

        return $this->registerSession($data, $this->model->DB());
    }

    /**
     * @param $pass
     * @return string
     */
    public static function getEncryptPass($pass){
        $semilla = "ZDQ3ZmRmZTM1MjIxODk0MWUxNDRlMGQ4YmMzZTBlZjI=";
        return hash('sha256',md5(sha1($pass). sha1($semilla)));
    }
}