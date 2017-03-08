<?php

namespace fw_Gunicorn\kernel\engine\middleware;

class Urls{
    private $_controller = array();
    private $_pattern = array();
    private $current_url = "/";
    private $_params_url = array(); // un array de como esta compuesta la url
    private $_params_controller  = array(); // parametros que se le pasara al controlador
    private $_instanceMiddleware;

    public function __construct(){
        /* captura la url invocada en el browser */
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
        if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
        $uri = '/' . trim($uri, '/');

        $this->current_url = $uri;

        /* se crea  array como "/" que compone la url */
        $this->_params_url = $this->getCreateParams($this->current_url);

    }

    /**
     * Create an array from a url structure
     */
    private function getCreateParams($url){
        if($url == '/')
            return;
        $array_tmp =  explode('/', filter_var(rtrim($url,'/'),FILTER_SANITIZE_URL));


        foreach ($array_tmp as $key => $value){
            if(empty($value)){
                unset($array_tmp[$key]);
            }
        }
        $array = array_values($array_tmp);
        return $array;
    }

    /**
     * @param $url Pattern to be fulfilled by the url in the browser
     * @param null $controller
     */
    public function add($url, $controller = null, $instanceClass = null){
        $this->setUrl($url);

        $this->setController($controller);
        $this->_instanceMiddleware = $instanceClass;
    }

    /**
     * Process the url request
     */
    public function submit(){

        foreach ($this->_pattern as $key => $value){
            
            if($value == ''){
                if(preg_match("#^/$value$#", $this->current_url)){
                    $this->instanceController($key);
                }
            }else{
                /* Create an array for each pattern written in urls file */
                $patter = $this->getCreateParams($value);
                /* verifica que el patro escrito en el urls file coincide con los parametros de la url */
               if( $this->getMatchPatters($patter) ){

                   /* si la url coincide con el patron escrito en el archivo de url entonces se instancia el controlador */
                   $this->instanceController($key);
               }

            }
        }
    }
    private function getMatchPatters($patters){
        /* cantidad de parametros del patron de url */


        foreach ($patters as $key => $condicion){
            /* se obtine el contenido de la url */
            $value = $this->_params_url[$key];     

            /* indice del array es 0 y no coincide el primer parametro de la url retorna 404 */
            if($key == 0 && !preg_match('/^'.$condicion.'+$/i', $value)){
                /*404*/
                return false;

            }
            $parames_evaluated = trim(str_replace('}','', str_replace('P({','', $value)));
            if(!preg_match('/^'.$parames_evaluated.'+$/i', $value)){
                /*404*/
                return false;
            }

            /* identificar si el patron es un parametro que debe ser enviado al controlador */
            $p = "P{";
            $posicion_coincidencia = strpos($condicion, $p);
            if ($posicion_coincidencia !== false) {
                $this->_params_controller[] = $value;
            }
        }
        return true;
    }

    private function instanceController($indx){        
        $controller = explode('.',$this->_controller[$indx]);

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

        if(!empty($method)){

            if( count($this->_params_controller) > 0 ){
                /* hay que mejorar */
                switch ( count($this->_params_controller) ) {
                    case 1:
                        if (!empty($this->_instanceMiddleware)){
                            $controller->$method(
                                $this->_instanceMiddleware,
                                $this->_params_controller[0]);
                        }else{
                            $controller->$method($this->_params_controller[0]);
                        }
                        break;
                    case 2:
                        if (!empty($this->_instanceMiddleware)){
                            $controller->$method(
                                $this->_instanceMiddleware,
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
                        if (!empty($this->_instanceMiddleware)){
                            $controller->$method(
                                $this->_instanceMiddleware,
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
                if(!empty($this->_instanceMiddleware))
                    $controller->$method($this->_instanceMiddleware);
                else
                    $controller->$method();
            }

        }
    }
    private function includeController($path){

    }
    /**
     * @param array $controller
     */
    private function setController($controller)
    {
        $this->_controller[] = $controller;
    }

    /**
     * @param array $url
     */
    private function setUrl($url)
    {
        $this->_pattern[] = $url;
    }
}
