<?php
namespace bpteam\GenRegEx;

use \PHPUnit_Framework_Testcase;
use \ReflectionClass;

class GenRegExTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param        $name
     * @param string $className
     * @return \ReflectionMethod
     */
    protected static function getMethod($name, $className = 'bpteam\BigList\JsonList')
    {
        $class = new ReflectionClass($className);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * @param        $name
     * @param string $className
     * @return \ReflectionProperty
     */
    protected static function getProperty($name, $className = 'bpteam\BigList\JsonList')
    {
        $class = new ReflectionClass($className);
        $property = $class->getProperty($name);
        $property->setAccessible(true);
        return $property;
    }

    public function testHtmlTag()
    {
        $gen = new GenRegEx();
        $regEx = $gen->htmlTag('<div class="hello world" id="test_id">');
        $this->assertEquals('<div[^>]*\s*[^>]*class\s*=\s*["\']?hello\s*world["\']?\s*[^>]*id\s*=\s*["\']?test_id["\']?[^>]*>', $regEx);
    }
}