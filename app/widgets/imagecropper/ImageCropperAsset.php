<?php 
namespace app\widgets\imagecropper;
use yii\web\AssetBundle;

class ImageCropperAsset extends AssetBundle
{
   public $sourcePath = '@app/widgets/imagecropper/assets';

    public $css = [
        'css/cropper.min.css',
        'css/imagecropper.css',
    ];
    public $js = [
      'js/cropper.min.js',
      'js/dropzone.min.js',
      'js/webcam.min.js',
      
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
?>