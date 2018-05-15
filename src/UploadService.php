<?php
namespace Ycn\Qiniu;

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use yii\helpers\ArrayHelper;

/**
 * 图片上传对象
 *
 * User: huangxianan <xianan_huang@163.com>
 * Date: 2018/4/23
 * Time: 下午3:26
 */

class UploadService
{
    /**
     * 鉴权对象
     *
     * @var Auth
     */
    private $auth;

    /**
     * 上传管理对象注入
     *
     * @var UploadManager
     */
    private $uploadManager;

    /**
     * 上传token
     *
     * @var string
     */
    private $uploadToken;

    /**
     * 资源管理类
     * @var
     */
    private $bucketManager;
    /**
     * 配置
     *
     * @var array
     */
    private $config = [];

    /**
     * 实例对象
     *
     * @var object
     */
    static private $_instance;


    private function __construct()
    {
    }

    /**
     * 防止克隆对象
     */
    private function __clone()
    {
    }

    static public function getInstance($accessKey, $secretKey, $bucket)
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();

            //加载配置
            $_config = require(__DIR__ . '/Config.php');
            self::$_instance->setConfig($_config);

            //注入auth类
            self::$_instance->setAuth($accessKey,$secretKey);

            //生成token
            self::$_instance->setToken($bucket);

            //注入上传管理类
            self::$_instance->setUploadManager();

            //注入资源管理类
            self::$_instance->setBucketManager();

        }

        return self::$_instance;
    }

    /**
     * 注入 auth 类
     *
     * @param $accessKey
     * @param $secretKey
     */
    public function setAuth($accessKey, $secretKey)
    {
        //注入Auth类
        $this->auth = new Auth($accessKey, $secretKey);
    }

    /**
     * 生成token
     *
     * @param $bucket
     */
    public function setToken($bucket)
    {
        //生成token
        $this->uploadToken = $this->auth->uploadToken($bucket);
    }

    /**
     * 注入上传管理类
     */
    public function setUploadManager()
    {
        $this->uploadManager = new UploadManager();
    }

    /**
     * 注入资源管理类
     */
    public function setBucketManager()
    {
        $this->bucketManager = new BucketManager($this->auth);
    }

    /**
     * 设置配置
     *
     * @param $config
     */
    public function setConfig(array $config)
    {
        //加载配置
        $this->config = ArrayHelper::merge($this->config, $config);

    }

    /**
     * 获取uploadToken
     *
     * @return string
     */
    public function getToken()
    {
        return $this->uploadToken;
    }


    /**
     * 文件上传接口
     *
     * @param $uploadFileName
     * @param $filePath
     * @return object
     * @throws \Exception
     */
    public function upload($filePath, $uploadFileName = '')
    {

        list($width, $height, $type, $attr) = getimagesize($filePath);

        if(!isset($this->config['supportImgType'][$type])){

            throw new \Exception('图片类型不支持');
        }

        $uploadFileName = !empty($uploadFileName) ? $uploadFileName : date("YmdHis").rand(10000,99999).'.'.$this->config['supportImgType'][$type];

        //上传文件获取返回
        list($response, $error) = $this->uploadManager->putFile($this->uploadToken, $uploadFileName, $filePath);

        //如果有错误返回错误
        if( $error !== null){
            throw new \Exception($error->message());
        }

        $response['filename'] = $uploadFileName;
        return $response;
    }

    /**
     * 删除资源接口
     *
     * @param $bucket
     * @param $key
     */
    public function delete($bucket, $key)
    {
        $this->bucketManager->delete($bucket, $key);
    }

}