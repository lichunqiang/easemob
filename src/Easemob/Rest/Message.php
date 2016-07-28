<?php

/*
 * This file is part of the light/easemob.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace light\Easemob\Rest;

use light\Easemob\Message\Message as Msg;

class Message extends Rest
{
    /**
     * Send a message.
     *
     * @param Msg $msg
     *
     * @return mixed This will return the data field.
     */
    public function send(Msg $msg)
    {
        $response = $this->http->post('messages', $msg->build());

        return $this->parseResponse($response)['data'];
    }
}
