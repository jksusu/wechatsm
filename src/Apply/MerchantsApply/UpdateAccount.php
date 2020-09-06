<?php
declare (strict_types=1);
/**
 * This file is part of the jksusu/wechatsm.
 *
 * @link     https://github.com/jksusu/wechatsm
 * @license  https://github.com/jksusu/wechatsm/blob/master/LICENSE
 */

namespace Wechatsm\Apply\MerchantsApply;

use Wechatsm\Apply\Exceptions\InvalidArgumentException;
use Wechatsm\Apply\Http\HttpClient;

class UpdateAccount
{
    use HttpClient;

    public function updateAccount($baseUrl, $data)
    {
        if (!isset($data['sub_merchants'])) {
            throw new InvalidArgumentException('sub_merchants non-existent');
        }
        $url = $baseUrl . '/sub_merchants/' . $data['sub_merchants'] . '/modify-settlement';
        return $this->createPost($url, $data);
    }
}