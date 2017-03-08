<?php

namespace fw_Gunicorn\kernel\engine\middleware;

class Request{
    private $post = array();
    private $files = array();
    private $get = array();
    private $type_request = '';
    private static $excepCSRFTOKEN = array();
    
    public function __construct()
    {
        /* detecta si se hizo algun request */

        if( (count($_GET) > 0 || count($_POST) > 0) ){
            if(!Request::checkCSRFTOKEN()){
                return;
            }

        }

        if(isset($_GET) && count($_GET) > 0){
            $this->setGet();
        }
        if(isset($_POST) && count($_POST) > 0)
            $this->setPost();

        if(isset($_FILES) && count($_FILES) > 0)
            $this->setFiles();

    }
    private static function checkCSRFTOKEN(){
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
        if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
        $uri = '/' . trim($uri, '/');
        if(in_array($uri, Request::$excepCSRFTOKEN)){
            return true;
        }
        if(!isset($_REQUEST['csrftoken']) || $_REQUEST['csrftoken'] != $_COOKIE['csrftoken']){
            die('
                    <h1>forbidden 403</h1>
                    <p>CSRFTOKEN in '.$uri.'</p>
                ');
        }



        return true;
    }
    public function isPost(){
        if($this->type_request == 'POST')
            return true;
        else
            return false;
    }
    public function _post($key){
        return $this->post[$key];

    }
    public function _postAll(){
        return $this->post;

    }
    public function _get($key){
        return $this->get[$key];

    }
    public function _getAll(){
        return $this->post;

    }
    public function _files($key){
        return $this->files[$key];

    }
    public function _filesAll(){
        return $this->files;

    }
    private function setFiles(){
        foreach ($_FILES as $key => $value){
            $this->files[$key] = $value;
            unset($_FILES[$key]);
        }
    }
    private function setPost(){
        foreach ($_POST as $key => $value){
            $this->post[$key] = $value;
            unset($_POST[$key]);
            unset($_REQUEST[$key]);
        }
    }
    private function setGet(){
        foreach ($_GET as $key => $value){
            $this->get[$key] = $value;
            unset($_GET[$key]);
            unset($_REQUEST[$key]);
        }
    }
    public static function excepCSRFTOKEN($form){
        self::$excepCSRFTOKEN[] = $form;
    }
}
