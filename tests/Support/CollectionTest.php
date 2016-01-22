<?php

namespace tests\Support;

use light\Easemob\Support\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testSetable()
    {
        $collection = new Collection(['name' => 'light']);

        $this->assertTrue(isset($collection['name']));
        $this->assertEquals($collection['name'], 'light');

        $this->assertNull($collection['notexits']);

        return $collection;
    }

    /**
     * @depends testSetable
     */
    public function testJsonable($collection)
    {
        $json = json_encode($collection);
        $this->assertTrue(is_string($json));
        $this->assertEquals($json, json_encode(['name' => 'light']));
    }

    /**
     * @depends testSetable
     */
    public function testSerialize($collection)
    {
        $res = serialize($collection);
        $this->assertEquals($res, 'C:32:"light\Easemob\Support\Collection":29:{a:1:{s:4:"name";s:5:"light";}}');
    }
}
