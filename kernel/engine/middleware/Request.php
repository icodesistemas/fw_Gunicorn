<?php

namespace fw_Gunicorn\kernel\engine\middleware;

class Request{
    private $post = array();
    private $files = array();
    private $get = array();

    public function __construct()
    {
        if(isset($_GET) && count($_GET) > 0)
            $this->setGet();

        if(isset($_POST) && count($_POST) > 0)
            $this->setPost();

        if(isset($_FILES) && count($_FILES) > 0)
            $this->setFiles();

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
        }
    }
    private function setGet(){
        foreach ($_GET as $key => $value){
            $this->get[$key] = $value;
            unset($_GET[$key]);
        }
    }
}
