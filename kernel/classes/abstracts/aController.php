<?php
namespace fw_Gunicorn\kernel\classes\abstracts;

use fw_Gunicorn\kernel\engine\dataBase;
use fw_Gunicorn\kernel\engine\middleware\Response;
use Twig_Loader_Filesystem;
use Twig_Environment;

abstract class aController{

    #private $path_app;
    private $db;
    private $dir_template;


    public function __construct($app){
        $this->setStartEngineTemplate($app);
        /* negar que el controlador se conecte a la db
         * if(defined ('DATABASE')){
            $this->db = new dataBase\ConexionDataBase();
        }*/

    }
    /**
    * Return Object DataBase
    */
    /*public function DB(){
        if(!defined ('DATABASE')){
            die('The connection to database is not defined');
        }
        return $this->db;
    }*/
    private function addContextToken($context){
        /* si la cookie de token esta definida la agrega al contexto del template para que pueda ser usada en el template */
        if(isset($_SESSION['csrftoken'])){
            if(!empty($context))
                $context = array_merge($context,array('csrftoken' => $_SESSION['csrftoken'])) ;
            else
                $context = array('csrftoken' => $_SESSION['csrftoken']);
        }
        return $context;
    }
    private function addContextMessage($context){
        /* verifica si hay algun mensaje que enviar al template lo agrega al template */
        if(!empty(Response::getMessage()))
            $context = array_merge($context, array('message' => Response::getMessage())) ;

        return $context;
    }
    public function render($template, $context = null){
        /* se identifica el nombre del dominio de la aplicacion web */
        if(!defined('DOMAIN_NAME') ||  empty(DOMAIN_NAME)){
            die('Sorry, in the settings.php file you must define the constant DOMAIN_NAME or give a value to that constant');
        }

        if(DOMAIN_NAME == 'http://localhost'){
            die('To localhost add the root folder of the web project');
        }

        $twig = new Twig_Environment($this->loader_template);

        $context = $this->addContextToken($context);
        $context = $this->addContextMessage($context);

        if(!empty($context)){
            $tpl = $twig->render($template.'.twig', $context);
        }else{
            $tpl = $twig->render($template.'.twig');
        }
        $this->setCompleteLinkUrl($tpl);
        

    }
    private function setCompleteLinkUrl($template){
        $url_static_fiel = DOMAIN_NAME . '/' . STATICFILES_DIRS;

        $tpl = str_replace('{ DOMAIN_NAME }',DOMAIN_NAME,str_replace('{DOMAIN_NAME}', DOMAIN_NAME, $template));
        $tpl = str_replace('{ STATICFILE_DIR }',$url_static_fiel,str_replace('{STATICFILE_DIR}', $url_static_fiel, $tpl));
        echo $tpl;
    }   
    /*public function setPathApplication($path){
        $this->path_app = $path;

    }*/
    private function setStartEngineTemplate($app){
        $this->dir_template = $app . TEMPLATE_DIR;
        if(!file_exists($this->dir_template)){
            die('El directorio para las vistas no esta creado, consulte el archivo settings.php y cree el directorio por favor');
        }
        $this->loader_template = new Twig_Loader_Filesystem($this->dir_template);
    }

}