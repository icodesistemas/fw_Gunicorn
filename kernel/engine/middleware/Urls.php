<?php

namespace fw_Gunicorn\kernel\engine\middleware;

class Urls{
    private $_pattern = array();
    private $current_url = "/";
    private $_instanceMiddleware = array();

    public function __construct(){
        /* captura la url invocada en el browser */
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
        if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
        $uri = '/' . trim($uri, '/');

        $this->current_url = $uri;



    }
    /**
     * @param $url Pattern to be fulfilled by the url in the browser
     * @param null $controller
     */
    public function add($url, $controller = null, $instanceClass = null){
        $this->setUrl(array($url => $controller));


        if(!empty($instanceClass))
            $this->_instanceMiddleware = array_merge($this->_instanceMiddleware, array($url =>$instanceClass));
    }

    /**
     * Process the url request
     */
    public function submit(){
        /*echo '<pre>';
        print_r($this->_pattern);
        die();*/
        foreach ($this->_pattern as $key => $value){
            /*if($value == ''){

                if(preg_match("#^/$value$#", $this->current_url)){
                    $this->instanceController($key);
                    break;
                }
            }*/
            /* verificar si el patron de url definidor en el urls.php es el mismo que la url que se esta solicitando */
            $current_url =  trim($this->current_url,'/');
            $value_pattern = str_replace('/','\/',trim($key,'/'));

            /* quitar la sintaxis de P{[]} para expresiones regulares */
            $value_pattern = str_replace('P{','', $value_pattern);

            $value_pattern = '/^'.trim(str_replace('}','', $value_pattern),'/');

            /*evalua que la url que se esta pidiendo sea la que cumpla algun patron de url definido en el urls.php del proyecto */

            if(preg_match($value_pattern.'$/', $current_url)){
                $p = 'P{';
                $posicion_inicial_parametro = strpos($key, $p);
                if ($posicion_inicial_parametro !== false) {
                    $posicion_cerrar = strpos($key, '}');
                    $parametro = str_replace('P{','',substr($key, $posicion_inicial_parametro, $posicion_cerrar));
                    $parametro = str_replace('}','', $parametro);


                    if(preg_match($parametro, $current_url, $matches, null, 0)){
                        $this->_params_controller  = $matches;

                    }
                }

                $this->instanceController($value);

            }
            //if( '/'.trim($this->current_url,'/') == '/'.trim($value,'/')){

                /* Create an array for each pattern written in urls file */

                /*$patter = $this->getCreateParams($value);
                /* verifica que el patro escrito en el urls file coincide con los parametros de la url */
                //if( $this->getMatchPatters($patter) ){
                    /* si la url coincide con el patron escrito en el archivo de url entonces se instancia el controlador */
                //    $this->instanceController($key);
                //    break;
                //}
            //}
        }

    }
    private function instanceController($controller){
        $controller = explode('.',$controller);

        /* index 0 is application name */
        $application = $controller[0];

        /* index 1 is class name controller and filename php */
        $class_controller = $controller[1];

        /* index 2 is the method of the class controller */
        if(isset($controller[2])){
            $method = $controller[2];
        }else{
            $method = '';
        }
        $namespace = 'apps\\' .$application .'\\controllers\\'.$class_controller;

        /*  validar que la aplicacion realmente este en el directorio correcto*/
        if(!file_exists(BASE_DIR . 'apps/' . $application)){
            die('La aplicación ' . $application . ' no se encuentra instalada');
        }
        $controller = new $namespace(BASE_DIR . 'apps/' . $application . '/');
        //$controller->setPathApplication('apps\\' .$application .'\\');

        if(empty($method)){
            return;
        }
        $instans_request = false;

        foreach ($this->_instanceMiddleware as $key => $value){
            if($key == $this->current_url)
                $instans_request = true;
        }
        if( count($this->_params_controller) > 0 ){
            /* hay que mejorar */
            switch ( count($this->_params_controller) ) {
                case 1:
                    if($instans_request){
                        $controller->$method(
                            $this->_instanceMiddleware[$this->current_url],
                            $this->_params_controller[0]);
                    }else{
                        $controller->$method($this->_params_controller[0]);
                    }
                    break;
                case 2:
                    if($instans_request){
                        $controller->$method(
                            $this->_instanceMiddleware[$this->current_url],
                            $this->_params_controller[0],
                            $this->_params_controller[1]
                        );
                    }else{
                        $controller->$method(
                            $this->_params_controller[0],
                            $this->_params_controller[1] );
                    }

                    break;
                case 3:
                    if($instans_request){
                        $controller->$method(
                            $this->_instanceMiddleware[$this->current_url],
                            $this->_params_controller[0],
                            $this->_params_controller[1],
                            $this->_params_controller[2] );
                    }else{
                        $controller->$method(
                            $this->_params_controller[0],
                            $this->_params_controller[1],
                            $this->_params_controller[2] );
                    }

                    break;
                case 4:
                    $controller->$method( $this->_params_controller[0],
                        $this->_params_controller[1],
                        $this->_params_controller[2],
                        $this->_params_controller[3] );
                    break;
            }

        }else{
            if($instans_request)
                $controller->$method($this->_instanceMiddleware[$this->current_url]);
            else
                $controller->$method();
        }


    }

    /**
     * @param array $url
     */
    private function setUrl(Array $url)
    {
        $this->_pattern = array_merge($this->_pattern, $url);
    }
}
