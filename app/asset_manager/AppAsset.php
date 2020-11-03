<?php

namespace app\asset_manager;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'resources/css/site.css',
        'resources/css/font-awesome.min.css',
        'resources/dist/nestable/jquery.nestable.min.css',
    ];
    public $js = [
      'resources/js/app.js',
      'resources/dist/nestable/jquery.nestable.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
