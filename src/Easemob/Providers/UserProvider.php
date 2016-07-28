<?php

/*
 * This file is part of the light/easemob.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace light\Easemob\Providers;

use light\Easemob\Rest\User;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class UserProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['user'] = function ($pimple) {
            return new User($pimple['access_token'], $pimple['http']);
        };
    }
}
