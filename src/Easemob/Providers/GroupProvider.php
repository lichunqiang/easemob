<?php

namespace light\Easemob\Providers;

use light\Easemob\Rest\Group;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class GroupProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['group'] = function ($pimple) {
            return new Group($pimple['access_token'], $pimple['http']);
        };
    }
}
