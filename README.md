wechatsm
===============

> 运行环境要求PHP7.1+。
全称 weChat Special Merchant

## 主要功能

* 微信图片上传
* 商户进件(开发中)
## 安装

~~~
composer require jksusu/wechatsm
~~~

## 使用
```php
<?php
require 'vendor/autoload.php';

$privateKey = 'apiclient_key.pem';//密钥证书
$mchId = '商户号';
$apiCertificateNumber = 'api证书编号';

$img = 'https://csdnimg.cn/feed/20200817/23f9fef9eeb10a423a3e72bc60861ff9.jpg';
$upload = new Wechatsm\Upload\UploadPicture($privateKey, $mchId, $apiCertificateNumber);
$media_id = $upload->uploadImage($img);
var_dump($media_id);

打印结果:YL1ddCvzOslhsK3tCzRWNOYTbLcT_QEYBob94OpxdZpqoTmqZrtCWyml7LrKnuDFv1J0pWkmlqTo2_SBet_zyusNzjPSaUjNLUrfPUI5Htk
```
## 版权信息
MIT
