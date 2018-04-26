<?php
namespace Ycn\Qiniu;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/**
 * 七牛鉴权对象
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
     * @var
     */
    private $uploadToken;

    public function __construct($accessKey, $secretKey, $bucket)
    {
        //注入Auth类
        $this->auth = new Auth($accessKey, $secretKey);

        //生成token
        $this->uploadToken = $this->auth->uploadToken($bucket);

        //注入上传管理对象
        $this->uploadManager = new UploadManager();
    }

    /**
     * 获取uploadToken
     *
     * @return string
     */
    public function getToken()
    {
        $this->uploadToken;
    }


    /**
     * 文件上传接口
     *
     * @param $uploadFileName
     * @param $filePath
     * @return object
     * @throws \Exception
     */
    public function upload($uploadFileName, $filePath)
    {
        //上传文件获取返回
        list($response, $error) = $this->uploadManager->putFile($this->uploadToken, $uploadFileName, $filePath);

        //如果有错误返回错误
        if( $error !== null){
            throw new \Exception($error->message());
        }

        return $response;
    }

}