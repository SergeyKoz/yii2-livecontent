<?php
namespace ssoft\livecontent;

use yii\web\AssetBundle;

/**
 * Livecontent asset bundle
 *
 * @author Sergii Kozin <sergagame1@gmail.com>
 */
class ContentAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';
    
    public $js = [
        'dialog.js',    
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
