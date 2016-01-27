<?php

namespace light\Easemob\Providers;

use light\Easemob\Rest\ChatRoom;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ChatRoomProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['chatroom'] = function ($pimple) {
            return new ChatRoom($pimple['access_token'], $pimple['http']);
        };
    }

}
