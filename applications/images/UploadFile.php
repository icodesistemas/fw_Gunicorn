<?php
    namespace fw_Gunicorn\applications\images;
    /**
     * A class for the manipulation of images. Allows you to upload images to the server and resize
     * Class UploadFile
     * @package fw_Gunicorn\applications\images
     */

    class UploadFile{
        private $cluster;
        private $type_images = array('image/jpeg','image/jpg', 'image/png', 'image/gif');

        public function __construct(){
            /* valida si la extension GD esta instalada */
            if(!gd_info())
                die('Please install php-gd');

            if(!defined('CLUSTER_UPLOAD_IMAGES'))
                die('Please set the constant CLUSTER_UPLOAD in the settings.php file');

           /* $this->cluster = BASE_DIR . CLUSTER_UPLOAD;

            if(!file_exists($this->cluster))
                mkdir($this->cluster, 0755);*/

            $this->cluster = BASE_DIR . CLUSTER_UPLOAD_IMAGES . '/';
        }
        private function validateTypeImage($type){
            if(!in_array($type, $this->type_images))
                return false;
            else
                return true;
        }
        private function getImageCreate($type, $file){
            switch ($type){
                case 'image/jpeg':
                    return imagecreatefromjpeg($file);
                case 'image/jpg':
                    return imagecreatefromjpeg($file);
                case 'image/png':
                    return imagecreatefrompng($file);
                case 'image/gif':
                    return imagecreatefromgif($file);
            }
        }
        public function setUpload(Array $file, $encryp_name = true){
            /* validar si el archivo es permitido por la aplicacion */

            if(!$this->validateTypeImage($file['type']))
                throw new \Exception('File not support');

            /* actura el nombre y verifica si lo desean cifrado */
            $name_file = $file['name'];
            if($encryp_name)
                $name_file = md5($name_file . date('Y-m-d H:i:s'));
            else
                $name_file = str_replace(' ','-', $name_file);

            /* obtiene la informacion de la imagen */
            $data_image = getimagesize($file['tmp_name']);
            $width = $data_image[0];
            $height = $data_image[1];

            /* crea una nueva imagen con las medidas de la imagen que se esta subien al servidor */
            $imagen_p = imagecreatetruecolor($width, $height);
            $imagen = $this->getImageCreate($file['type'], $file['tmp_name']);

            imagecopyresampled($imagen_p, $imagen, 0, 0, 0, 0, $width, $height, $width, $height);
            imagepng($imagen_p, $this->cluster. $name_file . '.png');

            imagedestroy($imagen_p);
            imagedestroy($imagen);

            $this->resize($name_file . '.png', $name_file, 920);
            $this->resize($name_file . '.png', $name_file. '_sm', 200);
            $this->resize($name_file . '.png', $name_file. '_md', 480);

            return $name_file. '.png';

        }
        private function resize($image, $new_name, $width){
            $image = $this->cluster . $image;

            /* obtiene la informacion de la imagen */
            $data_image = getimagesize( $image);

            $wpercent = floatval($width / $data_image[0]);

            $hsize = intval($data_image[1] * $wpercent);

            $imagen_p = imagecreatetruecolor($width, $hsize);
            $imagen = imagecreatefrompng($image);

            imagecopyresampled($imagen_p, $imagen, 0, 0, 0, 0, $width, $hsize, $data_image[0], $data_image[1]);
            imagepng($imagen_p, $this->cluster. $new_name . '.png');

            imagedestroy($imagen_p);
            imagedestroy($imagen);
        }

        /**
         *Defines the type of image file that will allow uploading to the server
         * @param array $mime_type_images
         */
        public function setMIMEType(Array $mime_type_images){
            $this->type_images = $mime_type_images;
        }

        public function setDeleteImage($image){
            $image = explode('.', $image);
            $md = $image[0] . '_md.png';
            $sm = $image[0] . '_sm.png';
            $img = $image[0] . '.png';

            if(!unlink($this->cluster . '/'. $md))
                throw new \Exception('error delete file image');
            if(!unlink($this->cluster . '/'. $sm))
                throw new \Exception('error delete file image');
            if(!unlink($this->cluster . '/'. $img))
                throw new \Exception('error delete file image');

            return true;
        }
    }