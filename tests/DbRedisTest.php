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

}
 