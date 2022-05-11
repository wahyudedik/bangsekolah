<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\imagine\Image;

/**
 * TinymceController implements the CRUD actions for Setting model.
 * Read more detail
 * https://www.tiny.cloud/docs/advanced/php-upload-handler/
 */
class TinymceController extends Controller
{
    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }

    public function actionImageUploadUrl($directory)
    {
        /***************************************************
        * Only these origins are allowed to upload images *
        ***************************************************/
        $accepted_origins = array("http://localhost", "http://192.168.1.104", "http://192.168.1.105", "http://156.67.218.246", "http://thesmedia.id", "https://thesmedia.id");

        /*********************************************
        * Change this line to set the upload folder *
        *********************************************/
        // $imageFolder = "images/";
        $imageFolder = Yii::getAlias('@uploads/'.$directory.'/');

        reset ($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])){
            if (isset($_SERVER['HTTP_ORIGIN'])) {
              // same-origin requests won't set an origin. If the origin is set, it must be valid.
              if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
              } else {
                header("HTTP/1.1 403 Origin Denied");
                return;
              }
            }

            /*
              If your script needs to receive cookies, set images_upload_credentials : true in
              the configuration and enable the following two headers.
            */
            // header('Access-Control-Allow-Credentials: true');
            // header('P3P: CP="There is no P3P policy."');

            // Sanitize input
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                header("HTTP/1.1 400 Invalid file name.");
                return;
            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("jpg", "png", "jpeg", "JPG", "PNG", "JPEG"))) {
                header("HTTP/1.1 400 Invalid extension.");
                return;
            }

            // Accept upload if there was no origin, or if it is an accepted origin
            // $filetowrite = $imageFolder . $temp['name'];

            // Additional code from MAF
            $explodedImage = explode('.', $temp['name']);
            $extension = end($explodedImage);
            $generatedName = Yii::$app->security->generateRandomString() . ".{$extension}";
            $uploadPath = $imageFolder.$generatedName;

            move_uploaded_file($temp['tmp_name'], $uploadPath);

            // resize max resolution
            $imageSize = getimagesize($uploadPath);
            /**
             * Resize gambar maksimal besar 900x900
             * Tapi tetap menjaga rasio
             */
            $orientation = 'portrait';
            $width = $imageSize[0];
            $height = $imageSize[1];
            if ($imageSize[0] > $imageSize[1]) {
                $orientation = 'landscape';
            } else {
                $orientation = 'portrait';
            }
            if ($orientation == 'landscape') {
                if ($width > 900) {
                    $oldWidth = $width;
                    $width = 900;
                    $height = $height/($oldWidth/900);
                }
            } else if ($orientation == 'portrait') {
                if ($height > 900) {
                    $oldHeight = $height;
                    $height = 900;
                    $width = $width/($oldHeight/900);
                }
            }
            Image::thumbnail($uploadPath, $width, $height)->save($uploadPath, ['quality' => 100]);

            // Respond to the successful upload with JSON.
            // Use a location key to specify the path to the saved image resource.
            // { location : '/your/uploaded/image/file'}
            // echo json_encode(array('location' => $filetowrite));
            return json_encode(array('location' => $generatedName));
        } else {
            // Notify editor that the upload failed
            header("HTTP/1.1 500 Server Error");
        }
    }

}
