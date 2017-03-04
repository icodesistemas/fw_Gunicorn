<?php
namespace fw_Gunicorn\kernel\classes\abstracts;

use fw_Gunicorn\kernel\engine\dataBase;
use Twig_Loader_Filesystem;
use Twig_Environment;

abstract class aController{
    private $template;
    private $path_app;
    private $db;
    public function __construct($app){
        $this->setStartEngineTemplate($app);
        if(defined ('DATABASE')){
            $this->db = new dataBase\ConexionDataBase();
        }

    }
    public function DB(){
        if(!defined ('DATABASE')){
            die('The connection to database is not defined');
        }
        return $this->db;
    }
    public function render($template, $context = null){
        $twig = new Twig_Environment($this->loader_template);

        if(!empty($context)){
            echo $twig->render($template.'.twig', $context);
        }else{
            echo $twig->render($template.'.twig');
        }

    }
    public function setPathApplication($path){
        $this->path_app = $path;

    }
    private function setStartEngineTemplate($app){
        $dir_template = $app . TEMPLATE_DIR;
        if(!file_exists($dir_template)){
            die('El directorio para las vistas no esta creado, consulte el archivo settings.php y cree el directorio por favor');
        }
        $this->loader_template = new Twig_Loader_Filesystem($dir_template);
    }

}