<?php

namespace light\Easemob\Rest;

class Group extends Rest
{
    /**
     * Get all groups.
     *
     * @param null|string $cursor
     * @param int $limit
     *
     * @return array|bool
     */
    public function all($cursor = null, $limit = 20)
    {
        $response = $this->get('chatgroups', [
            'query' => [
                'limit' => $limit,
                'cursor' => $cursor,
            ],
        ]);

        if (false === $response) {
            return false;
        }

        return [
            'items' => $response['data'],
            'cursor' => isset($response['cursor']) ? $response['cursor'] : '',
        ];
    }

    /**
     * Fetch the group details
     *
     * @return bool|array
     */
    public function detail()
    {
        $args = func_get_args();
        if (empty($args)) {
            throw new \BadMethodCallException('At last one parameter should be set.');
        }
        $groups = implode(',', $args);
        $response = $this->get("chatgroups/{$groups}");

        return $response ? $response['data'] : false;
    }

    /**
     * @param $name
     * @param $desc
     * @param $owner
     * @param array $members
     * @param bool $is_public
     * @param int $max_users
     * @param bool $approval
     *
     * @return bool|array ['groupid' => 12312312]
     */
    public function create(
        $name,
        $desc,
        $owner,
        array $members = [],
        $is_public = false,
        $max_users = 200,
        $approval = false
    )
    {
        $response = $this->post('chatgroups', [
            'body' => json_encode([
                'groupname' => $name,
                'desc' => $desc,
                'public' => $is_public,
                'maxusers' => $max_users,
                'approval' => $approval,
                'owner' => $owner,
                'members' => $members,
            ]),
        ]);

        return $response ? $response['data'] : false;
    }

    /**
     * Update group.
     *
     * @param string $group_id
     * @param null|string $groupname
     * @param null|string $description
     * @param null|integer $maxusers
     *
     * @return bool
     */
    public function update($group_id, $groupname = null, $description = null, $maxusers = null)
    {
        $args = array_filter(compact('groupname', 'description', 'maxusers'));
        if (empty($args)) {
            throw new \BadMethodCallException('Empty parameter not allowed.');
        }
        $response = $this->put("chatgroups/{$group_id}", [
            'body' => json_encode($args),
        ]);

        return $response ? $response['data'] : false;
    }

    /**
     * Remove a group.
     *
     * @param string $group_id
     *
     * @return bool
     */
    public function remove($group_id)
    {
        $response = $this->delete("chatgroups/{$group_id}");

        return $response ? $response['data']['success'] : false;
    }

    /**
     * Get group's members.
     *
     * @param string $group_id
     *
     * @return bool|array
     */
    public function members($group_id)
    {
        $response = $this->get("chatgroups/{$group_id}/users");

        return $response ? $response['data'] : false;
    }

    /**
     * Join one user to a group.
     *
     * @param string $group_id
     * @param string $username
     *
     * @return bool
     */
    public function join($group_id, $username)
    {
        $response = $this->post("chatgroups/{$group_id}/users/{$username}");

        return $response ? $response['data']['result'] : false;
    }

    /**
     * Join multiple users to a group.
     *
     * @param string $group_id
     * @param array $users
     *
     * @return bool|array
     */
    public function batchJoin($group_id, array $users)
    {
        $response = $this->post("chatgroups/{$group_id}/users", [
            'body' => json_encode([
                'usernames' => $users,
            ]),
        ]);
        return $response ? $response['data'] : false;
    }

    /**
     * Remove one member from group.
     *
     * @param string $group_id
     * @param string $username
     *
     * @return bool
     */
    public function out($group_id, $username)
    {
        $response = $this->delete("chatgroups/{$group_id}/users/{$username}");

        return $response ? $response['data']['result'] : false;
    }

    /**
     * @param string $group_id
     * @param array $users
     *
     * @return bool|array
     */
    public function batchOut($group_id, array $users)
    {
        $users = implode(',', $users);
        $response = $this->delete("chatgroups/{$group_id}/users/{$users}");

        return $response ? $response['data'] : false;
    }

    /**
     * Get user joined groups.
     *
     * @param string $username
     *
     * @return bool|array
     */
    public function getUserGroups($username)
    {
        $response = $this->get("users/{$username}/joined_chatgroups");

        return $response ? $response['data'] : false;
    }

    /**
     * Transfer group owner.
     *
     * @param string $group_id
     * @param string $username
     *
     * @return bool
     */
    public function transfer($group_id, $username)
    {
        $response = $this->put("chatgroups/{$group_id}", [
            'body' => json_encode([
                'newowner' => $username,
            ]),
        ]);
        return $response ? $response['data']['newowner'] : false;
    }

    /**
     * Cat the group blocked users.
     *
     * @param string $group_id
     *
     * @return bool|array
     */
    public function blocks($group_id)
    {
        $response = $this->get("chatgroups/{$group_id}/blocks/users");

        return $response ? $response['data'] : false;
    }

    /**
     * Block user.
     *
     * @param string $group_id
     * @param string $username
     *
     * @return bool
     */
    public function block($group_id, $username)
    {
        $response = $this->post("chatgroups/{$group_id}/blocks/users/{$username}");

        return $response ? $response['data']['result'] : false;
    }

    /**
     * @param string $group_id
     * @param array $users
     *
     * @return bool|array
     */
    public function batchBlock($group_id, array $users)
    {
        $response = $this->post("chatgroups/{$group_id}/blocks/users", [
            'body' => json_encode([
                'usernames' => $users,
            ]),
        ]);

        return $response ? $response['data'] : false;
    }

    /**
     * @param string $group_id
     * @param string $username
     *
     * @return bool
     */
    public function unblock($group_id, $username)
    {
        $response = $this->delete("chatgroups/{$group_id}/blocks/users/{$username}");

        return $response ? $response['data']['result'] : false;
    }

    /**
     * @param string $group_id
     * @param array $users
     *
     * @return bool|array
     */
    public function batchUnblock($group_id, array $users)
    {
        $users = implode(',', $users);
        $response = $this->delete("chatgroups/{$group_id}/blocks/users/{$users}");

        return $response ? $response['data'] : false;
    }

}
