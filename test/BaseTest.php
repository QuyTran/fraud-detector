<?php
namespace Model;
class BaseTest extends \PHPUnit_Framework_TestCase
{
    public function testTest1()
    {
        $stub = $this->getMockForAbstractClass('\Model\Base');
        $this->assertEquals(2, $stub->test1(1));
    }
}
