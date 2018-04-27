<?php
namespace Ycn\Qiniu\Assets;

use yii\web\AssetBundle;

/**
 * Created by PhpStorm.
 *
 * @link http://www.yii-china.com/
 * @copyright Copyright (c) 2018 Yii中文网
 * @author huangxianan <xianan_huang@163.com>
 */

class UploadAsset extends AssetBundle
{
    public $css = [
        'css/site.css'
    ];

    public $js = [
        'js/itdoc.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];

    /**
     * 初始化：sourcePath赋值
     * @see \yii\web\AssetBundle::init()
     */
    public function init()
    {
        $this->sourcePath = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR . 'Statics';
    }
}
