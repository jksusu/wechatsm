<?php
declare (strict_types=1);
/**
 * This file is part of the jksusu/wechatsm.
 *
 * @link     https://github.com/jksusu/wechatsm
 * @license  https://github.com/jksusu/wechatsm/blob/master/LICENSE
 */

namespace Wechatsm\Apply;

use Wechatsm\Apply\Exceptions\InvalidArgumentException;

class Application
{
    protected $bashUrl = 'https://api.mch.weixin.qq.com/v3/applyment4sub';

    protected $provider = [
        'submitApplication' => 'Wechatsm\Apply\MerchantsApply\SubmitApplication',
    ];

    public function __call($function, $arguments)
    {
        // TODO: Implement __call() method.
        if (isset($this->provider[$function])) {
            throw new InvalidArgumentException($function . ' non-existent');
        }
        $namespace = $this->provider[$function];
        return (new $namespace)->$function($this->bashUrl, $arguments);
    }
}