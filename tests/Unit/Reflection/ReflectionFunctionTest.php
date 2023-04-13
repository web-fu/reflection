<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionFunction;
use WebFu\Reflection\ReflectionParameter;

class ReflectionFunctionTest extends TestCase
{
    public function setUp(): void
    {
        require_once __DIR__ . '/../../Fixtures/example.php';
    }

    public function testGetClosure(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('example', $reflectionFunction->getClosure()->__invoke('example'));
    }

    public function testGetParameters(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(new ReflectionParameter('example', 'param'), $reflectionFunction->getParameters()[0]);
    }

    public function testInvoke(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('example', $reflectionFunction->invoke('example'));
    }

    public function testInvokeArgs(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('example', $reflectionFunction->invokeArgs(['example']));
    }

    public function testIsAnonymous(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->isAnonymous());

        if (PHP_VERSION_ID < 80100) {
            $this->markTestSkipped('Anonymous functions are available in PHP 8.1+');
        }

        $reflectionFunction = new ReflectionFunction(fn () => null);

        $this->assertTrue($reflectionFunction->isAnonymous());
    }
}
