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

    public function testGetAttributes(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals([], $reflectionFunction->getAttributes());
    }

    public function testGetClosureScopeClass(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(null, $reflectionFunction->getClosureScopeClass());
    }

    public function testGetClosureThis(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(null, $reflectionFunction->getClosureThis());
    }

    public function testGetClosureUsedVariables(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals([], $reflectionFunction->getClosureUsedVariables());
    }

    public function testGetDocComment(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('/** @param class-string $param */', $reflectionFunction->getDocComment());
    }

    public function testGetEndLine(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(9, $reflectionFunction->getEndLine());
    }

    public function testGetExtension(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(null, $reflectionFunction->getExtension());
    }

    public function testGetExtensionName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(null, $reflectionFunction->getExtensionName());
    }

    public function testGetFileName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertStringContainsString('/Fixtures/example.php', $reflectionFunction->getFileName());
    }

    public function testGetName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('example', $reflectionFunction->getName());
    }

    public function testGetNamespaceName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('', $reflectionFunction->getNamespaceName());
    }

    public function getShortName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('example', $reflectionFunction->getShortName());
    }

    public function testGetStartLine(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(6, $reflectionFunction->getStartLine());
    }

    public function testGetStaticVariables(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals([], $reflectionFunction->getStaticVariables());
    }

    public function testGetTentativeReturnType(): void
    {
        $this->markTestIncomplete();
    }

    public function testHasReturnType(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertTrue($reflectionFunction->hasReturnType());
    }

    public function testInNameSpace(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->inNamespace());
    }

    public function testIsClosure(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(false, $reflectionFunction->isClosure());
    }

    public function testIsDeprecated(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(false, $reflectionFunction->isDeprecated());
    }

    public function testIsGenerator(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(false, $reflectionFunction->isGenerator());
    }

    public function testIsInternal(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(false, $reflectionFunction->isInternal());
    }

    public function testIsUserDefined(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(true, $reflectionFunction->isUserDefined());
    }

    public function testIsVariadic(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(false, $reflectionFunction->isVariadic());
    }

    public function testReturnsReference(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(false, $reflectionFunction->returnsReference());
    }

    public function testGetClosure(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        assert($reflectionFunction->getClosure() instanceof \Closure);

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
        if (PHP_VERSION_ID < 80200) {
            $this->markTestSkipped('Anonymous functions are not available for PHP version lower than 8.2.0');
        }

        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->isAnonymous());

        $reflectionFunction = new ReflectionFunction(fn () => null);

        $this->assertTrue($reflectionFunction->isAnonymous());
    }
}
