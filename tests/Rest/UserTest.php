<?php

namespace tests\Rest;

use phpDocumentor\Reflection\DocBlock\Tag\VarTag;
use tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @var \light\Easemob\Rest\User
     */
    protected $user;

    protected $testUsername = 'sdkTest';

    public function setUp()
    {
        parent::setUp();

        $this->user = $this->easemob->user;
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->user->remove($this->testUsername);
        $this->user = null;
    }

    public function testRemove()
    {
        $this->user->register(['username' => 'remove', 'password' => '123132']);
        $result = $this->user->remove('remove');
        $this->assertTrue($result);
    }

    public function testRegister()
    {
        $result = $this->user->register(['username' => $this->testUsername, 'password' => '123123']);
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('uuid', $result[0]));

        $new = $this->user->one($this->testUsername);
        $this->assertEquals($new['uuid'], $result[0]['uuid']);

    }

    public function testNoRegisterUser()
    {
        $result = $this->user->one('unicorn');
        $this->assertFalse($result);
    }

    public function testRegisterBatch()
    {
        $users = $this->mockUsers();

        $result = $this->user->register($users);

        var_dump($result);

        $this->assertEquals(count($users), count($result));

        return $result;
    }

    /**
     * @depends testRegisterBatch
     *
     * @param array $result
     */
    public function testAll($result)
    {
        $result = $this->user->all(null, 2);
        $this->assertNotFalse($result);
        $this->assertTrue(array_key_exists('cursor', $result));
        $this->assertEquals(2, count($result['items']));
    }

    /**
     * @depends testRegisterBatch
     *
     * @param array $result
     */
    public function testRemoveBatch($result)
    {
        $result = $this->user->batchRemove(2);

        $this->assertNotFalse($result);
        $this->assertEquals(2, count($result));
    }

    public function testResetPassword()
    {
        $this->user->register(['username' => 'restpassword', 'password' => 123123123]);

        $result = $this->user->resetPassword('restpassword', 123123123, 'cjandfsfas');

        $this->assertTrue($result);

        return 'restpassword';
    }

    /**
     * @depends testResetPassword
     *
     * @param string $username
     *
     * @return string
     */
    public function testUpdateNickName($username)
    {
        $result = $this->user->updateNickname($username, 'LightTest');

        $this->assertTrue($result);

        return $username;
    }

    /**
     * @depends testUpdateNickName
     * @param string $username
     */
    public function testIsOnline($username)
    {
        $result = $this->user->isOnline($username);
        $this->assertFalse($result);
    }


    public function mockUsers()
    {
        return [
            ['username' => 'mock1', 'password' => 'mock1'],
            ['username' => 'mock2', 'password' => 'mock2'],
            ['username' => 'mock3', 'password' => 'mock3'],
        ];
    }
}
