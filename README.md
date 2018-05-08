图片上传 yii2-qiniu-upload
====
yii2图片上传到七牛的扩展

@from Yii中文网（www.yii-china.com）

安装（Installation）
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yii-china/yii2-qiniu-upload "~1.0.0"
```

or add

```
"yii-china/yii2-qiniu-upload": "~1.0.0"
```

to the require section of your `composer.json` file.


用法（Usage）
-----

view
---

```php

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    ...

    <?= $form->field($model, 'image')->widget(\Ycn\Qiniu\UploadWidget::className(),['options'=>[
            'domain' => 'http://img.itdocs.org'  //七牛图片服务域名
    ]]) ?>
    
    ...

<?php ActiveForm::end(); ?>
```

controller
---

```php
//获取文件上传对象
$file = UploadedFile::getInstance($model, 'image');

//实例化上传对象
$up = UploadService::getInstance(
  Yii::$app->params['qiniu']['ak'],  //七牛的AK
  Yii::$app->params['qiniu']['sk'],  //七牛的SK
  Yii::$app->params['qiniu']['bucket']  //七牛的BUCKET
);

//可以自定义图片文件名 作为upload第二个参数
//$fileName = date("YmdHis").rand(10000,99999).'.jpg';

//图片上传临时路径
$filePath= $file->tempName;

//调用upload上传图片到七牛
$response = $up->upload($filePath);

return $response;
```












