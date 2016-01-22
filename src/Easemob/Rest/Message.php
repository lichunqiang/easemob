<?php

namespace light\Easemob\Rest;

use light\Easemob\Message\Message as Msg;

class Message extends Rest
{
    /**
     * Send a message
     *
     * @param  Msg    $msg
     *
     * @return mixed This will return the data field.
     */
    public function send(Msg $msg)
    {
        $response = $this->http->post('messages', $msg->build());

        return $this->parseResponse($response)['data'];
    }

    /**
     * 获取历史聊天记录
     * {@inheritdoc}
     *
     * @param  [type]  $cursor
     * @param  integer $limit
     *
     * @return [type]          [description]
     */
    public function history($cursor = null, $sql = null, $limit = 20)
    {
        $response = $this->parseResponse(
            $this->http->get('chatmessages', [
                'limit' => $limit,
                'cursor' => $cursor,
                'sql' => $sql,
            ])
        );

        return [
            'items' => $response['entities'],
            'cursor' => isset($response['cursor']) ? $response['cursor'] : null,
        ];
    }
}
