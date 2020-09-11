<?php
declare (strict_types=1);
/**
 * This file is part of the jksusu/wechatsm.
 *
 * @link     https://github.com/jksusu/wechatsm
 * @license  https://github.com/jksusu/wechatsm/blob/master/LICENSE
 */

namespace Wechatsm\Apply\Http;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use WechatPay\GuzzleMiddleware\WechatPayMiddleware;
use WechatPay\GuzzleMiddleware\Util\PemUtil;

trait HttpClient
{
    /**
     * 商户号
     * @var string
     */
    protected $merchantId = '';

    /**
     * 商户API证书序列号
     * @var string
     */
    protected $merchantSerialNumber = '';

    protected $key_pem = '';
    protected $cert_pem = '';

    protected $client = '';

    public function getInstance()
    {
        if (is_null($this->client)) {

            $merchantPrivateKey = PemUtil::loadPrivateKey($this->key_pem); //商户私钥
            $wechatpayCertificate = PemUtil::loadCertificate($this->cert_pem); //微信支付平台证书

            $wechatpayMiddleware = WechatPayMiddleware::builder()
                ->withMerchant($this->merchantId, $this->merchantSerialNumber, $merchantPrivateKey) //传入商户相关配置
                ->withWechatPay([$wechatpayCertificate]) //可传入多个微信支付平台证书，参数类型为array
                ->build();

            $stack = HandlerStack::create();
            $stack->push($wechatpayMiddleware, 'wechatpay');

            $this->client = new Client(['handler' => $stack]);
        }
        return $this;
    }

    public function createPost($url, array $data)
    {

    }

    public function createGet($url, array $data)
    {

    }
}