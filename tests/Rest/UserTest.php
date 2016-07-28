<?php

/*
 * This file is part of the light/easemob.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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

        $this->user->remove($this->testUsername);
        foreach ($this->mockUsers() as $item) {
            $this->user->remove($item['username']);
        }
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
        $this->assertSame($new['uuid'], $result[0]['uuid']);
    }

    public function testNoRegisterUser()
    {
        $result = $this->user->one('unicorn');
        $this->assertFalse($result);
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
        $this->assertSame(2, count($result));
    }

    public function mockUsers()
    {
        return [
            ['username' => 'moccck1', 'password' => 'mock1'],
            ['username' => 'moccck2', 'password' => 'mock2'],
            ['username' => 'moccck3', 'password' => 'mock3'],
        ];
    }
}
