<?php
    namespace fw_Gunicorn\kernel\engine\projects;

    class CreateProject{
        public function __construct($name, $base_dir){
            $this->name  = $name;
            $this->base_dir  = $base_dir;

            $this->setCreateDirectories();

        }

        /**
         * Create directories and files initial for project
         */
        private function setCreateDirectories(){
            $name_project = $this->base_dir . ucwords($this->name) . '_Project';
            $file_htaccess = __DIR__ . '/modelProject/htaccess.txt';

            /* create folder for project */
            if(!mkdir($name_project, 0755)){
                exit('Could not create project directory');
            }

            /* create htaccess file for project */
            copy($file_htaccess, $this->base_dir . '.htaccess');

            /* create folder static file for project */
            if(!mkdir($this->base_dir . 'static', 0755)){
                exit('Could not create directory for static fiel');
            }

            /* create folder static file js for project */
            if(!mkdir($this->base_dir . 'static/js', 0755)){
                exit('Could not create directory for static fiel js');
            }
            /* create folder static file css for project */
            if(!mkdir($this->base_dir . 'static/css', 0755)){
                exit('Could not create directory for static fiel css');
            }
            /* create folder static file images for project */
            if(!mkdir($this->base_dir . 'static/images', 0755)){
                exit('Could not create directory for static fiel images');
            }

            /* create example file settings for project */
            #copy($file_settings, $name_project . '/settings.php');
            $this->setCreateFileSettingProject($name_project);

            /* create example file urls for project */
            $this->setCreateUrlFiel($name_project . '/urls.php');

            $this->setCreateFileIndexProject();


        }
        private function getGenerateSecretKey(){
            $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; 
            $numerodeletras=10; 
            $semilla = "ZDQ3ZmRmZTM1MjIxODk0MWUxNDRlMGQ4YmMzZTBlZjI=";
            $cadena = ""; //variable para almacenar la cadena generada
            for($i=0;$i<$numerodeletras;$i++){
                $cadena .= substr($caracteres,rand(0,strlen($caracteres)),1); /*Extraemos 1 caracter de los caracteres
            entre el rango 0 a Numero de letras que tiene la cadena */
            }
            return hash('sha256',md5(sha1($cadena). sha1($semilla)));
            
        }
        private function setCreateFileSettingProject($dir){
            $file_setting = __DIR__ . '/modelProject/settings.txt';
            $file_setting_project = $dir . '/settings.php';
            $params = array(
                'secret_key' => $this->getGenerateSecretKey(),
                'project_name' => ucwords($this->name)
            );

            $reader = fopen($file_setting, 'r');
            $index_example = fread($reader, filesize($file_setting));
            fclose($reader);

            /* create index file settings for project */
            $index_project = fopen($file_setting_project, 'w');
            foreach ($params as $key => $value) {
                $line = str_replace('{%' . $key .'%}', $value, $index_example);
                fwrite($index_project, $line);
            }
            fclose($index_project);
        }
        private function setCreateFileIndexProject(){
            $file_index = __DIR__ . '/modelProject/index.txt';
            $file_index_project = $this->base_dir . 'index.php';
            $params = array(
                'name_project' => ucwords($this->name) . '_Project'
            );

            $reader = fopen($file_index, 'r');
            $index_example = fread($reader, filesize($file_index));
            fclose($reader);

            /* create index file settings for project */
            $index_project = fopen($file_index_project, 'w');
            foreach ($params as $key => $value) {
                $line = str_replace('{%' . $key .'%}', $value, $index_example);
                fwrite($index_project, $line);
            }
            fclose($index_project);
        }
        private function setCreateUrlFiel($url_file){
            $file_url_project = $url_file;
            $file_url = __DIR__ . '/modelProject/urls.txt';
            $reader = fopen($file_url, 'r');
            $url_example = fread($reader, filesize($file_url));
            fclose($reader);

            $params = array(
                'project_name' => $this->name,
            );
            /* create file urls for project */

            $url_app = fopen($file_url_project, 'w');
            foreach ($params as $key => $value) {
                $line = str_replace('{%' . $key .'%}', $value, $url_example);
                fwrite($url_app, $line);
            }
            fclose($url_app);
        }
    }