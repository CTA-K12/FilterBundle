<?php

namespace Mesd\FilterBundle\Tests\Entity;

use Mesd\FilterBundle\Entity\Filter;

/**
 * @covers Mesd\FilterBundle\Entity\Filter::__construct
 */
class FilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * Sets up the tests
     */
    protected function setUp()
    {
        $this->filter = new Filter();
    }
    /**
     * Tears down the tests
     */
    protected function tearDown()
    {
        unset($this->filter);
    }

    /**
     * @covers Mesd\FilterBundle\Entity\Filter::__toString
     */
    public function testToString()
    {
        $name = 'Some Name';
        $this->filter->setName($name);
        $this->assertEquals($name, (string)$this->filter);
    }

    /**
     * @covers Mesd\FilterBundle\Entity\Filter::getId
     */
    public function testGetIdStartsWithNull()
    {
        $this->assertNull($this->filter->getId());
    }
}
