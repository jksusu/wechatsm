<?php
declare (strict_types=1);

namespace Wechatsm\Sign;

trait SignGenerate
{
    private $header;

    private $body;

    private $boundary;

    private $mchId;

    private $timeStamp;

    private $privateKey;

    private $sign;

    private $apiCertificateNumber;

    /**
     * 微信签名生成文档
     * @link https://wechatpay-api.gitbook.io/wechatpay-api-v3/qian-ming-zhi-nan-1/qian-ming-sheng-cheng
     */
    private function genSign()
    {
        $url_parts = parse_url($this->uploadAPi);
        $canonical_url = ($url_parts['path'] . (!empty($url_parts['query']) ? "?${url_parts['query']}" : ""));
        $message =
            'POST' . "\n" .
            $canonical_url . "\n" .
            $this->timeStamp . "\n" .
            $this->boundary . "\n" .
            json_encode($this->params) . "\n";
        openssl_sign($message, $raw_sign, $this->privateKey, 'sha256WithRSAEncryption');
        $this->sign = sprintf('mchid="%s",nonce_str="%s",timestamp="%d",serial_no="%s",signature="%s"',
            $this->mchId, $this->boundary, $this->timeStamp, $this->apiCertificateNumber, base64_encode($raw_sign));
        return $this;
    }

    private function setHeader()
    {
        $this->header = [
            'User-Agent' => 'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'Authorization: WECHATPAY2-SHA256-RSA2048 ' . $this->sign,
            'Content-Type: multipart/form-data;boundary=' . $this->boundary
        ];
        return $this;
    }

    private function setBody()
    {
        $boundaryStr = "--{$this->boundary}\r\n";
        $body = $boundaryStr;
        $body .= 'Content-Disposition: form-data; name="meta"' . "\r\n";
        $body .= 'Content-Type: application/json' . "\r\n";
        $body .= "\r\n";
        $body .= json_encode($this->params) . "\r\n";
        $body .= $boundaryStr;
        $body .= 'Content-Disposition: form-data; name="file"; filename="' . $this->params['filename'] . '"' . "\r\n";
        $body .= 'Content-Type:image/jpeg;' . "\r\n";
        $body .= "\r\n";
        $body .= file_get_contents($this->params['filePath']) . "\r\n";
        $body .= "--{$this->boundary}--\r\n";
        $this->body = $body;
        return $this;
    }
}