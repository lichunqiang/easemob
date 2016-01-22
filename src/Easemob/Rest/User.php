<?php

namespace light\Easemob\Rest;

/**
 * User operation
 */
class User extends Rest
{
    /**
     * Register users
     *
     * 通过「授权注册」的方式注册用户.
     *
     * @param  array $users
     *
     *  [['username' => '123123', 'password' => 'password', 'nickname' => 'niamee']]
     *
     * @return array The entities of registed user
     */
    public function register($users)
    {
        $response = $this->parseResponse(
            $this->http->post(
                'users',
                $users
            )
        );

        return $response['entities'];
    }

    /**
     * Fetch all user records.
     *
     * @param  string|null $cursor
     * @param  integer $limit
     *
     * @return array
     */
    public function all($cursor = null, $limit = 20)
    {
        $response = $this->http->get('users', ['limit' => $limit, 'cursor' => $cursor]);

        $result = $this->parseResponse($response);

        return [
            'items' => $result['entities'],
            'cursor' => isset($result['cursor']) ? $result['cursor'] : null,
        ];
    }

    /**
     * 获取单个用户信息
     *
     * @param  string $username
     *
     * @return mixed
     */
    public function one($username)
    {
        $response = $this->http->get("users/{$username}");

        $result = $this->parseResponse($response);

        return array_shift($result['entities']);
    }

    /**
     * 删除单个用户
     *
     * @param  string $username
     *
     * @return boolean
     */
    public function delete($username)
    {
        $response = $this->http->delete("users/{$username}");

        // $result = $this->parseResponse($response);
        //成功后不报错说明删除成功
        return true;
    }

    /**
     * 批量删除用户, 可一次删除N个用户, N建议值在100~500之间.
     *
     * 需要通过返回值来确定哪些用户被删除了.
     *
     * @param  integer $count 删除个数
     *
     * @return array  成功删除用户信息列表
     */
    public function batchDelete($count = 100)
    {
        $response = $this->http->delete('users', ['query' => ['limit' => $count]]);

        $result = $this->parseResponse($response);

        return $result['entities'];
    }

    /**
     * 重置用户密码
     *
     * @param string $username
     * @param string $password
     * @param string $newPassword
     *
     * @return boolean
     */
    public function resetPassword($username, $password, $newPassword)
    {
        $response = $this->http->put(
            "users/{$username}/{$password}",
            [
                'body' => json_encode(['newpassword' => $newPassword]),
            ]
        );

        $result = $this->parseResponse($response);

        return true;
    }

    /**
     * 更新用户昵称
     *
     * @param  string $username
     * @param  string $nickname
     *
     * @return boolean
     */
    public function updateNickname($username, $nickname)
    {
        $response = $this->http->put(
            "users/{$username}",
            [
                'body' => ['nickname' => $nickname],
            ]
        );

        $result = $this->parseResponse($response);

        return !empty($result['entities']);
    }

    /**
     * 为用户添加好友
     *
     * @param string $owner_name      用户名
     * @param string $friend_username 被添加为好友的用户名
     *
     * @return boolean
     */
    public function addFriend($owner_name, $friend_username)
    {
        $response = $this->http->post(
            "users/{$owner_name}/contacts/users/{$friend_username}"
        );

        $result = $this->parseResponse($response);

        return !empty($result['entities']);
    }

    /**
     * 解除用户好友关系
     *
     * @param  string $owner_name
     * @param  string $friend_username
     *
     * @return boolean
     */
    public function removeFriend($owner_name, $friend_username)
    {
        $response = $this->http->post("users/{$owner_name}/contacts/users/{$friend_username}");

        $result = $this->parseResponse($response);

        return !empty($result['entities']);
    }

    /**
     * 查看某个用户的好友
     *
     * @param  string $username
     *
     * @return array 好友用户名列表
     */
    public function friends($username)
    {
        $response = $this->http->get("users/{$username}/contacts/users");

        $result = $this->parseResponse($response);

        return $result['data'];
    }

    /**
     * 查看某一个IM用户的黑名单
     *
     * @param  string $username
     *
     * @return array 黑名单中的用户名列表
     */
    public function blocks($username)
    {
        $response = $this->http->get("users/{$username}/blocks/users");

        $result = $this->parseResponse($response);

        return $result['data'];
    }

    /**
     * 为用户加入黑名单
     *
     * @param  string $username
     * @param  array $users 需要加入黑名单中的用户名
     *
     * @return array 已经加入到黑名单中的用户
     */
    public function block($username, $users)
    {
        $response = $this->http->post(
            "users/{$username}/blocks/users",
            [
                'username' => $users,
            ]
        );

        $result = $this->parseResponse($response);

        return $result['data'];
    }

    /**
     * 解除黑名单
     *
     * @param  string $username
     * @param  string $target
     *
     * @return boolean
     */
    public function unblock($username, $target)
    {
        $response = $this->http->delete("users/{$username}/blocks/users/{$target}");

        return true;
    }

    /**
     * 查看用户是否在线
     *
     * @param  string  $username
     *
     * @return boolean
     */
    public function isOnline($username)
    {
        $response = $this->http->get("users/{$username}/status");

        $result = $this->parseResponse($response);

        return 'online' === $result['data'][$username];
    }

    /**
     * 获取用户离线消息数
     *
     * @param  string $username
     *
     * @return integer
     */
    public function offlineMsgCount($username)
    {
        $response = $this->http->get("users/{$username}/offline_msg_count");

        $result = $this->parseResponse($response);

        return (int) $result['data'][$username];
    }

    /**
     * 禁用账户
     *
     * @param  string $username
     *
     * @return boolean
     */
    public function disable($username)
    {
        $response = $this->http->post("users/{$username}/deactive");

        return true;
    }

    /**
     * 解禁账户
     *
     * @param  string $username
     *
     * @return boolean
     */
    public function enable($username)
    {
        $response = $this->http->post("users/{$username}/active");

        return true;
    }

    /**
     * 强制下线用户
     *
     * @param  string $username
     *
     * @return boolean
     */
    public function disconnect($username)
    {
        $response = $this->http->get("users/{$username}/disconnect");

        $result = $this->parseResponse($response);

        return $result['data'][$username];
    }
}
