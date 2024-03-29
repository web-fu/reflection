<?php

declare(strict_types=1);

/**
 * This file is part of web-fu/reflection
 *
 * @copyright Web-Fu <info@web-fu.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebFu\Tests\Unit\Reflection;

use Closure;
use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionFunction;
use WebFu\Reflection\ReflectionParameter;
use WebFu\Reflection\WrongPhpVersionException;

/**
 * @covers \WebFu\Reflection\ReflectionFunction
 */
class ReflectionFunctionTest extends TestCase
{
    private $filename = __DIR__.'/../../Fixtures/example.php';

    protected function setUp(): void
    {
        require_once $this->filename;
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getAttributes
     */
    public function testGetAttributes(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals([], $reflectionFunction->getAttributes());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getClosureScopeClass
     */
    public function testGetClosureScopeClass(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertNull($reflectionFunction->getClosureScopeClass());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getClosureScopeClass
     */
    public function testGetClosureThis(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertNull($reflectionFunction->getClosureThis());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getClosureUsedVariables
     */
    public function testGetClosureUsedVariables(): void
    {
        if (PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Function closures are not available for PHP versions lower than 8.1.0');
        }

        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals([], $reflectionFunction->getClosureUsedVariables());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getDocComment
     */
    public function testGetDocComment(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('/** @param class-string $param */', $reflectionFunction->getDocComment());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getEndLine
     */
    public function testGetEndLine(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(9, $reflectionFunction->getEndLine());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getExtension
     */
    public function testGetExtension(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertNull($reflectionFunction->getExtension());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getExtensionName
     */
    public function testGetExtensionName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertNull($reflectionFunction->getExtensionName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getFileName
     */
    public function testGetFileName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertStringContainsString('/Fixtures/example.php', $reflectionFunction->getFileName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getFileName
     */
    public function testGetName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('example', $reflectionFunction->getName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getNamespaceName
     */
    public function testGetNamespaceName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('', $reflectionFunction->getNamespaceName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getShortName
     */
    public function getShortName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('example', $reflectionFunction->getShortName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getStartLine
     */
    public function testGetStartLine(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(6, $reflectionFunction->getStartLine());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getStaticVariables
     */
    public function testGetStaticVariables(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals([], $reflectionFunction->getStaticVariables());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getTentativeReturnType
     */
    public function testGetTentativeReturnType(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::hasReturnType
     */
    public function testHasReturnType(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertTrue($reflectionFunction->hasReturnType());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::inNamespace
     */
    public function testInNameSpace(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->inNamespace());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::isClosure
     */
    public function testIsClosure(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->isClosure());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::isDeprecated
     */
    public function testIsDeprecated(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->isDeprecated());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::isGenerator
     */
    public function testIsGenerator(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->isGenerator());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::isInternal
     */
    public function testIsInternal(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->isInternal());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::isUserDefined
     */
    public function testIsUserDefined(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertTrue($reflectionFunction->isUserDefined());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::isVariadic
     */
    public function testIsVariadic(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->isVariadic());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::returnsReference
     */
    public function testReturnsReference(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->returnsReference());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getClosure
     */
    public function testGetClosure(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        assert($reflectionFunction->getClosure() instanceof Closure);

        $this->assertEquals('example', $reflectionFunction->getClosure()->__invoke('example'));
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::getParameters
     */
    public function testGetParameters(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(new ReflectionParameter('example', 'param'), $reflectionFunction->getParameters()[0]);
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::invoke
     */
    public function testInvoke(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('example', $reflectionFunction->invoke('example'));
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::invokeArgs
     */
    public function testInvokeArgs(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('example', $reflectionFunction->invokeArgs(['example']));
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::invokeArgs
     */
    public function testIsAnonymous(): void
    {
        if (PHP_VERSION_ID < 80200) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('Anonymous functions are not available for PHP versions lower than 8.2.0');

            $reflectionFunction = new ReflectionFunction('example');
            $reflectionFunction->isAnonymous();

            $this->markTestSkipped('Anonymous functions are not available for PHP versions lower than 8.2.0');
        }

        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->isAnonymous());

        $reflectionFunction = new ReflectionFunction(fn () => null);

        $this->assertTrue($reflectionFunction->isAnonymous());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::__debugInfo
     */
    public function testDebugInfo(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals([
            'name'        => 'example',
            'annotations' => ['@param class-string $param'],
            'attributes'  => [],
        ], $reflectionFunction->__debugInfo());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionFunction::__toString
     */
    public function testToString(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $filename = realpath($this->filename);

        $expected = <<<EOT
            /** @param class-string \$param */
            Function [ <user> function example ] {
              @@ $filename 6 - 9

              - Parameters [2] {
                Parameter #0 [ <required> string \$param ]
                Parameter #1 [ <optional> int \$default = 0 ]
              }
              - Return [ string ]
            }

            EOT;

        $this->assertEquals($expected, $reflectionFunction->__toString());
    }
}
