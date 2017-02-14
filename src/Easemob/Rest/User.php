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
 * User operation.
 */
class User extends Rest
{
    /**
     * Register users.
     *
     * 通过「授权注册」的方式注册用户.
     *
     * @param array $users
     *
     *  [['username' => '123123', 'password' => 'password', 'nickname' => 'niamee']]
     *
     * @return array|bool The entities of registed user
     */
    public function register($users)
    {
        $response = $this->post('users', $users);

        return $response ? $response['entities'] : false;
    }

    /**
     * Fetch all user records.
     *
     * @param string|null $cursor
     * @param int         $limit
     *
     * @return array|bool
     */
    public function all($cursor = null, $limit = 20)
    {
        $result = $this->get('users', ['limit' => $limit, 'cursor' => $cursor]);

        if (false === $result) {
            return false;
        }

        return [
            'items' => $result['entities'],
            'cursor' => isset($result['cursor']) ? $result['cursor'] : null,
        ];
    }

    /**
     * 获取单个用户信息.
     *
     * @param string $username
     *
     * @return mixed
     */
    public function one($username)
    {
        $result = $this->get("users/{$username}");

        return $result ? array_shift($result['entities']) : false;
    }

    /**
     * 删除单个用户.
     *
     * @param string $username
     *
     * @return bool
     */
    public function remove($username)
    {
        $response = $this->delete("users/{$username}");

        return $response !== false;
    }

    /**
     * 批量删除用户, 可一次删除N个用户, N建议值在100~500之间.
     *
     * 需要通过返回值来确定哪些用户被删除了.
     *
     * @param int $count 删除个数
     *
     * @return array|bool 成功删除用户信息列表
     */
    public function batchRemove($count = 100)
    {
        $result = $this->delete('users', ['query' => ['limit' => $count]]);

        return $result ? $result['entities'] : false;
    }

    /**
     * 重置用户密码
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function resetPassword($username, $password)
    {
        $response = $this->put(
            "users/{$username}/password",
            [
                'body' => json_encode(['newpassword' => $password]),
            ]
        );

        return $response !== false;
    }

    /**
     * 更新用户昵称.
     *
     * @param string $username
     * @param string $nickname
     *
     * @return bool
     */
    public function updateNickname($username, $nickname)
    {
        $response = $this->put(
            "users/{$username}",
            [
                'body' => json_encode(['nickname' => $nickname]),
            ]
        );

        return $response ? !empty($response['entities']) : false;
    }

    /**
     * 为用户添加好友.
     *
     * @param string $owner_name      用户名
     * @param string $friend_username 被添加为好友的用户名
     *
     * @return bool
     */
    public function addFriend($owner_name, $friend_username)
    {
        $response = $this->post(
            "users/{$owner_name}/contacts/users/{$friend_username}"
        );

        return $response ? !empty($response['entities']) : false;
    }

    /**
     * 解除用户好友关系.
     *
     * @param string $owner_name
     * @param string $friend_username
     *
     * @return bool
     */
    public function removeFriend($owner_name, $friend_username)
    {
        $response = $this->delete("users/{$owner_name}/contacts/users/{$friend_username}");

        return $response ? !empty($response['entities']) : false;
    }

    /**
     * 查看某个用户的好友.
     *
     * @param string $username
     *
     * @return array|bool 好友用户名列表
     */
    public function friends($username)
    {
        $response = $this->get("users/{$username}/contacts/users");

        return $response ? $response['data'] : false;
    }

    /**
     * 查看某一个IM用户的黑名单.
     *
     * @param string $username
     *
     * @return array|bool 黑名单中的用户名列表
     */
    public function blocks($username)
    {
        $response = $this->get("users/{$username}/blocks/users");

        return $response ? $response['data'] : false;
    }

    /**
     * 为用户加入黑名单.
     *
     * @param string $username
     * @param array  $users    需要加入黑名单中的用户名
     *
     * @return array|bool 已经加入到黑名单中的用户
     */
    public function block($username, $users)
    {
        $response = $this->post(
            "users/{$username}/blocks/users",
            [
                'usernames' => $users,
            ]
        );

        return $response ? $response['data'] : false;
    }

    /**
     * 解除黑名单.
     *
     * @param string $username
     * @param string $target
     *
     * @return bool
     */
    public function unblock($username, $target)
    {
        $response = $this->delete("users/{$username}/blocks/users/{$target}");

        return $response !== false;
    }

    /**
     * 查看用户是否在线
     *
     * @param string $username
     *
     * @return bool
     */
    public function isOnline($username)
    {
        $response = $this->get("users/{$username}/status");

        if (false === $response) {
            return false;
        }

        return 'online' === $response['data'][$username];
    }

    /**
     * 获取用户离线消息数.
     *
     * @param string $username
     *
     * @return int
     */
    public function offlineMsgCount($username)
    {
        $response = $this->get("users/{$username}/offline_msg_count");

        if (false === $response) {
            return false;
        }

        return (int) $response['data'][$username];
    }

    /**
     * @param string $username
     * @param string $msg_id
     *
     * @return string|bool delivered or undelivered
     */
    public function offlineMsgStatus($username, $msg_id)
    {
        $response = $this->get("users/{$username}/offline_msg_status/{$msg_id}");

        return $response ? $response['data'][$username] : false;
    }

    /**
     * 禁用账户.
     *
     * @param string $username
     *
     * @return bool
     */
    public function disable($username)
    {
        $response = $this->post("users/{$username}/deactivate");

        return $response !== false;
    }

    /**
     * 解禁账户.
     *
     * @param string $username
     *
     * @return bool
     */
    public function enable($username)
    {
        $response = $this->post("users/{$username}/activate");

        return $response !== false;
    }

    /**
     * 强制下线用户.
     *
     * @param string $username
     *
     * @return bool
     */
    public function disconnect($username)
    {
        $response = $this->get("users/{$username}/disconnect");

        return $response ? $response['data']['result'] : false;
    }
}
