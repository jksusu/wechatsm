<?php
declare (strict_types=1);

namespace Wechatsm\Upload;

use Wechatsm\Exceptions\HttpException;

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


    public function __construct($privateKey, string $mchId, string $apiCertificateNumber)
    {
        $this->timeStamp = time();
        $this->privateKey = openssl_get_privatekey(file_get_contents($privateKey));
        $this->apiCertificateNumber = $apiCertificateNumber;
        $this->mchId = $mchId;
        $this->boundary = uniqid();
        $this->genSign()->setHeader()->setBody();
    }

    public function uploadImage(string $picturePath)
    {
        return $this->params = [
            'filename' => basename($picturePath),
            'sha256' => hash_file('sha256', $picturePath),
        ];

        return $this->curl();
    }


    private function curl()
    {
        $ch = curl_init($this->uploadAPi);
        curl_setopt_array($ch, [
            'CURLOPT_HTTPHEADER' => $this->header,
            'CURLOPT_POSTFIELDS' => $this->body,
            'CURLOPT_TIMEOUT' => 30,
        ]);
        $response = curl_exec($ch);

        if ($error = curl_error($ch)) {
            throw new HttpException($error);
        }
        curl_close($ch);
        $res = json_decode($response, true);
        if (isset($res['media_id'])) {
            return $res['media_id'];
        }
        throw new HttpException($res);
    }
}