<?php
declare (strict_types=1);

namespace Wechatsm\Upload;

use Wechatsm\Exceptions\HttpException;
use Wechatsm\Exceptions\InvalidArgumentException;

class UploadPicture
{
    use \Wechatsm\Sign\SignGenerate;

    /**
     * 图片上传api地址
     * @var string
     * @link https://pay.weixin.qq.com/wiki/doc/apiv3/wxpay/tool/chapter3_1.shtml
     */
    private $uploadAPi = 'https://api.mch.weixin.qq.com/v3/merchant/media/upload';

    private $params;

    protected $extension = [
        'jpg' => 'jpg',
        'JPG' => 'JPG',
        'bmp' => 'bmp',
        'BMP' => 'BMP',
        'png' => 'png',
        'PNG' => 'PNG',
    ];

    public function __construct($privateKey, string $mchId, string $apiCertificateNumber)
    {
        $this->timeStamp = time();
        $this->privateKey = $privateKey;
        $this->apiCertificateNumber = $apiCertificateNumber;
        $this->mchId = $mchId;
        $this->boundary = uniqid();
    }

    public function uploadImage(string $picturePath)
    {
        if (!file_exists($this->privateKey)) {
            throw new InvalidArgumentException($this->privateKey . ' file not exists !!!');
        }
        $this->privateKey = openssl_get_privatekey(file_get_contents($this->privateKey));

        $extension = pathinfo($picturePath)['extension'];
        if (!array_key_exists($extension, $this->extension)) {
            throw new InvalidArgumentException('suffix support jpg bmp png !!!');
        }
        $this->params = [
            'filePath' => $picturePath,
            'filename' => basename($picturePath),
            'sha256' => hash_file('sha256', $picturePath),
        ];
        $this->genSign()->setHeader()->setBody();
        return $this->curl();
    }

    private function curl()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->uploadAPi);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        if ($error = curl_error($ch)) {
            throw new HttpException($error);
        }
        curl_close($ch);
        $res = json_decode($response, true);
        if (!isset($res['media_id'])) {
            throw new HttpException($res);
        }
        return $res['media_id'];
    }
}