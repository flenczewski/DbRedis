<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 2014-07-17
 * Time: 00:33
 */

class DbRedisTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var DbRedis handler
     */
    protected $redis;

    public function testClassExists()
    {
        $this->assertTrue(class_exists('DbRedis'));
    }

    /**
     * @expectedException DbRedisException
     */
    public function testConnectFail()
    {
        DbRedis::getInstance(array('host'=>'failhost'));
    }

    public function setUp()
    {
        $this->redis = DbRedis::getInstance();
    }

    public function providerRandomData()
    {
        return array(
            array('TestSet#1', 10, 0),
            array('TestSet#2', 10, 5),
            array('TestSet#3', 100, 10)
        );
    }

    /**
     * @dataProvider providerRandomData
     */
    public function testSet($key, $value)
    {
        $result = $this->redis->set($key, $value);
        $this->assertEquals($result, $value);
    }

    /**
     * @dataProvider providerRandomData
     */
    public function testGet($key, $value)
    {
        $result = $this->redis->get($key);
        $this->assertEquals($result, $value);
    }
}
 