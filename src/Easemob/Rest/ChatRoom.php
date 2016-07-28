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

/**
 * @see http://docs.easemob.com/doku.php?id=start:100serverintegration:70chatroommgmt#dokuwiki__top
 */
class ChatRoom extends Rest
{
    /**
     * @return bool|array
     */
    public function all()
    {
        $response = $this->get('chatrooms');

        return $response ? $response['data'] : false;
    }

    /**
     * @param string $room_id
     *
     * @return bool|array
     */
    public function detail($room_id)
    {
        $response = $this->get("chatrooms/{$room_id}");

        return $response ? $response['data'] : false;
    }

    /**
     * @param string $name
     * @param string $description
     * @param string $owner
     * @param int    $maxusers
     * @param array  $members
     *
     * @return bool|string Room id
     */
    public function create(
        $name,
        $description,
        $owner,
        $maxusers = 200,
        array $members = []
    ) {
        $args = compact('name', 'description', 'owner', 'maxusers', 'members');
        $response = $this->post('chatrooms', [
            'body' => json_encode($args),
        ]);

        return $response ? $response['data']['id'] : false;
    }

    /**
     * @param string      $room_id
     * @param null|string $name
     * @param null|string $description
     * @param null|int    $maxusers
     *
     * @return bool|array
     */
    public function update($room_id, $name = null, $description = null, $maxusers = null)
    {
        $args = array_filter(compact('name', 'description', 'maxusers'));
        if (empty($args)) {
            throw new \BadMethodCallException('Empty parameter not allowed.');
        }
        $response = $this->put("chatrooms/{$room_id}", [
            'body' => json_encode($args),
        ]);

        return $response ? $response['data'] : false;
    }

    /**
     * @param string $room_id
     *
     * @return bool
     */
    public function remove($room_id)
    {
        $response = $this->delete("chatrooms/{$room_id}");

        return $response ? $response['data']['success'] : false;
    }

    /**
     * @param string $username
     *
     * @return bool|array
     */
    public function getUserRooms($username)
    {
        $response = $this->get("users/{$username}/joined_chatrooms");

        return $response ? $response['data'] : false;
    }

    /**
     * @param string $room_id
     * @param string $username
     *
     * @return bool
     */
    public function join($room_id, $username)
    {
        $response = $this->post("chatrooms/{$room_id}/users/{$username}");

        return $response ? $response['data']['result'] : false;
    }

    /**
     * @param string $room_id
     * @param array  $users
     *
     * @return bool|array
     */
    public function batchJoin($room_id, array $users)
    {
        $response = $this->post("chatrooms/{$room_id}/users", [
            'body' => json_encode([
                'usernames' => $users,
            ]),
        ]);

        if (false === $response) {
            return false;
        }

        return $response['data']['newmembers'];
    }

    /**
     * @param string $room_id
     * @param string $username
     *
     * @return bool
     */
    public function out($room_id, $username)
    {
        $response = $this->delete("chatrooms/{$room_id}/users/{$username}");

        return $response ? $response['data']['result'] : false;
    }

    /**
     * @param string $room_id
     * @param array  $users
     *
     * @return bool|array
     */
    public function batchOut($room_id, array $users)
    {
        $users = implode(',', $users);
        $response = $this->delete("chatrooms/{$room_id}/users/{$users}");

        return $response ? $response['data'] : false;
    }
}
