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

class QueryApplication
{
    use HttpClient;

    protected $route = '/applyment/business_code/';

    public function businessQueryStatus($businessCode)
    {
        $url = $this->route . '?business_code=' . $businessCode;
        return $this->createGet($url);
    }

    public function applyQueryStatus($applymentId)
    {
        $url = $this->route . '?applyment_id=' . $applymentId;
        return $this->createGet($url);
    }
}