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

class Chat extends Rest
{
    /**
     * 获取历史聊天记录.
     *
     * @param null|string $cursor
     * @param null|string $sql
     * @param int         $limit
     *
     * @throws \light\Easemob\Exception\EasemobException
     *
     * @return array
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
