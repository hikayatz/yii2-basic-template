<?php

namespace app\asset_manager;

use yii\web\AssetBundle;

class ImagePickerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'resources/css/cropper.min.css',
        'resources/css/image_picker.css',
    ];
    public $js = [
      'resources/js/cropper.min.js',
      'resources/js/dropzone.min.js',
      'resources/js/webcam.min.js',
      
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
