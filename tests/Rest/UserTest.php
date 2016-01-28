<?php

namespace tests\Rest;

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

        $this->user->delete($this->testUsername);
        $this->user = null;
    }

    public function testRemove()
    {
        $this->user->register(['username' => 'remove', 'password' => '123132']);
        $result = $this->user->delete('remove');
        $this->assertTrue($result);
    }

    public function testRegister()
    {
        $result = $this->user->register(['username' => $this->testUsername, 'password' => '123123']);
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('uuid', $result[0]));
        return $result[0];
    }

    /**
     * @depends testRegister
     *
     * @param $user
     */
    public function testOne($user)
    {
        $result = $this->user->one($user['username']);
        $this->assertEquals($user['uuid'], $result['uuid']);
    }

    /**
     * @expectedException \light\Easemob\Exception\EasemobException
     */
    public function testNoRegisterUser()
    {
        $this->user->one('unicorn');
    }
}
