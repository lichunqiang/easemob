<?php

namespace light\Easemob\Providers;

use light\Easemob\Rest\Message;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MessageProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['message'] = function ($pimple) {
            return new Message($pimple['access_token'], $pimple['http']);
        };
    }
}
