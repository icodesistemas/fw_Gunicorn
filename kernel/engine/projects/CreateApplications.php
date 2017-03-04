<?php
    namespace fw_Gunicorn\kernel\engine\projects;

    class CreateApplications{
        public function __construct($name, $base_dir){
            $this->name  = $name;
            $this->base_dir  = $base_dir;
            $this->setCreateDirectories();

        }
        /**
        * Create directories and files initial for the application
        */
        private function setCreateDirectories(){
            /* verify if exist the directory for the applications */
            $folder_app = $this->base_dir . 'apps';
            if(!file_exists($folder_app)){
                mkdir($folder_app, 0755);
            }

            /* create folder for the new application */
            $folder_new_app = $folder_app .'/'. ucwords($this->name);
            mkdir($folder_new_app, 0755);

            /* create folder for the project templates */
            if(!file_exists($folder_new_app . '/templates'))
                mkdir($folder_new_app . '/templates', 0755);

            /* create folder for the application controllers*/
            mkdir($folder_new_app . '/controllers',0755);

            $this->setCreateControllers($folder_new_app . '/controllers');
        }

        private function setCreateControllers($folder_new_apps){

            $file_index = __DIR__ . '/modelProject/controller.txt';
            $folder_new_apps = $folder_new_apps . '/'. ucwords($this->name).'Controller.php';
            $params = array(
                'name_app' => ucwords($this->name)
            );

            $reader = fopen($file_index, 'r');
            $index_example = fread($reader, filesize($file_index));
            fclose($reader);

            /* create controller file for application */
            $controller = fopen($folder_new_apps, 'w');
            foreach ($params as $key => $value) {
                $line = str_replace('{%' . $key .'%}', $value, $index_example);
                fwrite($controller, $line);
            }
            fclose($controller);
        }
    }
?>