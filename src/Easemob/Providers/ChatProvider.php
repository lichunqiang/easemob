<?php

namespace light\Easemob\Providers;

use light\Easemob\Rest\Chat;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ChatProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['chat'] = function ($pimple) {
            return new Chat($pimple['access_token'], $pimple['http']);
        };
    }
}
