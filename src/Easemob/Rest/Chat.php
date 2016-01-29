<?php

namespace light\Easemob\Rest;

class Chat extends Rest
{
    /**
     * 获取历史聊天记录
     *
     * @param null|string $cursor
     * @param null|string $sql
     * @param  integer $limit
     *
     * @return array
     * @throws \light\Easemob\Exception\EasemobException
     */
    public function history($cursor = null, $sql = null, $limit = 20)
    {
        $response = $this->get('chatmessages', [
            'limit' => $limit,
            'cursor' => $cursor,
            'sql' => $sql,
        ]);

        return [
            'items' => $response['entities'],
            'cursor' => isset($response['cursor']) ? $response['cursor'] : null,
        ];
    }
}
