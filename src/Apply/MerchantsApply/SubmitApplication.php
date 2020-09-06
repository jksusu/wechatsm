<?php
declare (strict_types=1);
/**
 * This file is part of the jksusu/wechatsm.
 *
 * @link     https://github.com/jksusu/wechatsm
 * @license  https://github.com/jksusu/wechatsm/blob/master/LICENSE
 */

namespace Wechatsm\Apply\MerchantsApply;


use Wechatsm\Apply\Http\HttpClient;

class SubmitApplication
{
    use HttpClient;

    protected $route = 'https://api.mch.weixin.qq.com/v3/applyment4sub/applyment/';

    public function submitApplication(array $data)
    {
        return $this->createPost($this->route, $data);
    }
}