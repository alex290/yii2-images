yii2-images
===========

Модуль поддерживает библиотеки Imagick и GD, вы можете настроить его в настройках модуля.


Пример использования:
-------------

```php
$model = Model::findOne(12); //Model must have id

//If an image is first it will be main image for this model
$model->attachImage('../../image.png');

//But if you need set another image as main, use second arg
$model->attachImage('../../image2.png', true);

//get all images
$images = $model->getImages();
foreach($images as $img){
    //retun url to full image
    echo $img->getUrl();
    
    //return url to proportionally resized image by width
    echo $img->getUrl('300x');

    //return url to proportionally resized image by height
    echo $img->getUrl('x300');
    
    //return url to resized and cropped (center) image by width and height
    echo $img->getUrl('200x300');
}

//Returns main model image
$image = $model->getImage();

if($image){
    //get path to resized image 
    echo $image->getPath('400x300');
    
    //path to original image
    $image->getPathToOrigin();
    
    //will remove this image and all cache files
    $model->removeImage($image);
}

```

Детали
-------------
1. Получить изображения
    ```php
    $model->getImage(); //returns main image for model (first added image or setted as main)
    
    $model->getImages(); //returns array with images
    
    //If there is no images for model, above methods will return PlaceHolder images or null
    //If you want placeholder set up it in module configuration (see documentation)
    
    ```
2. Удалить изображение / изображения
    ```php
    $model->removeImage($image); //you must to pass image (object)
    
    $model->removeImages(); //will remove all images of this model
    ```

3. Установить основное изображение
    ```php
    $model->attachImage($absolutePathToImage, true); //will attach image and make it main
    
    foreach($model->getImages() as $img){
        if($img->getPrimaryKey() == $ourId){
            $model->setMainImage($img);//will set current image main
        }
    }
    ```

4. Получить размеры изображения
    ```php
    $image = $model->getImage();
    $sizes = $image->getSizes(); // Array. Original image sizes
    $sizes = $image->getSizesWhen('x500');
    echo '&lt;img width="'.$sizes['width'].'" height="'.$sizes['height'].'" src="'.$image->getUrl('x500').'" />';
    ```

5. Получить оригинальное изображение
    ```php
    $img = $model->getImage();
    echo $img->getPathToOrigin();
    ```


Установка
-------------
1. Запустить

        php composer.phar require --prefer-dist alex290/yii2-images "*"

    или добавить

	    "alex290/yii2-images": "*"

    в секцию require вашего `composer.json` файла.

2. run 
    <pre>
      php composer.phar update
    </pre>

3. run migrate
    <pre>
    php yii migrate/up --migrationPath=@vendor/alex290/yii2-images/migrations
    </pre>

4. Настройка модуля
    ```php
    'modules' => [
            'yii2images' => [
                'class' => 'alex290\yii2images\Module',
                //be sure, that permissions ok 
                //if you cant avoid permission errors you have to create "images" folder in web root manually and set 777 permissions
                'imagesStorePath' => 'images/store', //path to origin images
                'imagesCachePath' => 'images/cache', //path to resized copies
                'graphicsLibrary' => 'GD', //but really its better to use 'Imagick' 
                'placeHolderPath' => '@webroot/images/placeHolder.png', // if you want to get placeholder when image not exists, string will be processed by Yii::getAlias
                'imageCompressionQuality' => 100, // Optional. Default value is 85.
            ],
        ],
    ```

5. attach behaviour to your model (be sure that your model has "id" property)
    ```php
        public function behaviors()
        {
            return [
                'image' => [
                    'class' => 'alex290\yii2images\behaviors\ImageBehave',
                ]
            ];
        }
    ```

Thats all!


