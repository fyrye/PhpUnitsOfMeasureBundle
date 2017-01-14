<?php
/**
 * SimpleTest.php Description
 * @author Will Baumbach <will.baumbach@iselinc.com>
 * @version 2017.01.14
 */
namespace Fyrye\Bundle\PhpUnitsOfMeasureBundle\Tests;

class SimpleTest extends \PHPUnit_Framework_TestCase
{
    public function testResources()
    {
        $this->assertTrue(is_file(__DIR__.'/../Resources/config/services.xml'));
    }
}
