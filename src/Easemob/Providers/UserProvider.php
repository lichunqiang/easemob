<?php

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
