<?php
/**
 * Created by PhpStorm.
 * User: costa
 * Date: 25.06.14
 * Time: 15:35
 */

namespace alex290\yii2images\controllers;

use yii\web\Controller;
use yii;
use alex290\yii2images\models\Image;
use \alex290\yii2images\ModuleTrait;

class ImagesController extends Controller
{
    use ModuleTrait;
    public function actionIndex()
    {
        echo "Hello, man. It's ok, dont worry.";
    }

    public function actionTestTest()
    {
        echo "Hello, man. It's ok, dont worry.";
    }


    /**
     *
     * All we need is love. No.
     * We need item (by id or another property) and alias (or images number)
     * @param $item
     * @param $alias
     *
     */
    public function actionImageByItemAndAlias($item='', $dirtyAlias)
    {
        $dotParts = explode('.', $dirtyAlias);
        if(!isset($dotParts[1])){
            throw new \yii\web\HttpException(404, 'Image must have extension');
        }
        $dirtyAlias = $dotParts[0];

        $size = isset(explode('_', $dirtyAlias)[1]) ? explode('_', $dirtyAlias)[1] : false;
        $alias = isset(explode('_', $dirtyAlias)[0]) ? explode('_', $dirtyAlias)[0] : false;
        $image = $this->getModule()->getImage($item, $alias);

        if($image->getExtension() != $dotParts[1]){
            throw new \yii\web\HttpException(404, 'Image not found (extension)');
        }

        if($image){
            $response = \Yii::$app->response;
            $response->format = yii\web\Response::FORMAT_RAW;
            $response->headers->add('Content-Type', $image->getMimeType($size));
            $response->data = $image->getContent($size);
            return $response;
        }else{
            throw new \yii\web\HttpException(404, 'There is no images');
        }

    }
}
