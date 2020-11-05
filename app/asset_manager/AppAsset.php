<?php

namespace app\asset_manager;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'resources/css/site.css',
        'resources/dist/nestable/jquery.nestable.min.css',
    ];
    public $js = [
        'resources/js/app.js',
        'resources/js/datatable.js',
        'resources/js/blockUI.js',
        'resources/dist/nestable/jquery.nestable.min.js',
    ];
    public $depends = [
        'app\asset_manager\FontAwesome',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
