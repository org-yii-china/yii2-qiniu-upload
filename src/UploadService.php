<?php
namespace Ycn\Qiniu;

use Qiniu\Auth;
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
     * 配置
     *
     * @var array
     */
    private $config;

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
            self::$_instance->setToken($accessKey, $secretKey, $bucket);
            //注入上传管理对象
            self::$_instance->setUploadManager();
        }

        return self::$instance;
    }

    /**
     * @param $accessKey
     * @param $secretKey
     * @param $bucket
     */
    public function setToken($accessKey, $secretKey, $bucket)
    {
        //注入Auth类
        $this->auth = new Auth($accessKey, $secretKey);

        //生成token
        $this->uploadToken = $this->auth->uploadToken($bucket);
    }

    /**
     * 设置上传管理类
     */
    public function setUploadManager()
    {
        $this->uploadManager = new UploadManager();
    }

    /**
     * 设置配置
     *
     * @param $config
     */
    public function setConfig(array $config)
    {
        //加载配置
        $_config = require(__DIR__ . '/Config.php');
        $this->config = ArrayHelper::merge($_config, $config);

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

        if(!isset($this->config['imgType'][$type])){

            throw new \Exception('图片类型不支持');
        }

        $uploadFileName = !empty($uploadFileName) ?: date("YmdHis").rand(10000,99999).'.'.$this->imgType[$type];

        //上传文件获取返回
        list($response, $error) = $this->uploadManager->putFile($this->uploadToken, $uploadFileName, $filePath);

        //如果有错误返回错误
        if( $error !== null){
            throw new \Exception($error->message());
        }

        return $response;
    }

}