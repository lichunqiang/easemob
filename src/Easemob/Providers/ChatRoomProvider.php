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
