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

namespace WebFu\Reflection\Tests;

use Closure;
use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionFunction;
use WebFu\Reflection\ReflectionParameter;
use WebFu\Reflection\WrongPhpVersionException;

/**
 * @group unit
 *
 * @coversDefaultClass  \WebFu\Reflection\ReflectionFunction
 */
class ReflectionFunctionTest extends TestCase
{
    private const FILENAME = __DIR__.'/data/example.php';

    protected function setUp(): void
    {
        require_once self::FILENAME;
    }

    /**
     * @covers ::getAttributes
     */
    public function testGetAttributes(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals([], $reflectionFunction->getAttributes());
    }

    /**
     * @covers ::getClosureScopeClass
     */
    public function testGetClosureScopeClass(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertNull($reflectionFunction->getClosureScopeClass());
    }

    /**
     * @covers ::getClosureScopeClass
     */
    public function testGetClosureThis(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertNull($reflectionFunction->getClosureThis());
    }

    /**
     * @covers ::getClosureUsedVariables
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
     * @covers ::getDocComment
     */
    public function testGetDocComment(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('/** @param class-string $param */', $reflectionFunction->getDocComment());
    }

    /**
     * @covers ::getEndLine
     */
    public function testGetEndLine(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(9, $reflectionFunction->getEndLine());
    }

    /**
     * @covers ::getExtension
     */
    public function testGetExtension(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertNull($reflectionFunction->getExtension());
    }

    /**
     * @covers ::getExtensionName
     */
    public function testGetExtensionName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertNull($reflectionFunction->getExtensionName());
    }

    /**
     * @covers ::getFileName
     */
    public function testGetFileName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertStringContainsString('/data/example.php', $reflectionFunction->getFileName());
    }

    /**
     * @covers ::getFileName
     */
    public function testGetName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('example', $reflectionFunction->getName());
    }

    /**
     * @covers ::getNamespaceName
     */
    public function testGetNamespaceName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('', $reflectionFunction->getNamespaceName());
    }

    /**
     * @covers ::getShortName
     */
    public function getShortName(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('example', $reflectionFunction->getShortName());
    }

    /**
     * @covers ::getStartLine
     */
    public function testGetStartLine(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(6, $reflectionFunction->getStartLine());
    }

    /**
     * @covers ::getStaticVariables
     */
    public function testGetStaticVariables(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals([], $reflectionFunction->getStaticVariables());
    }

    /**
     * @covers ::getTentativeReturnType
     */
    public function testGetTentativeReturnType(): void
    {
        if (PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Function closures are not available for PHP versions lower than 8.1.0');
        }

        $reflectionFunction = new ReflectionFunction('date');
        $this->assertNull($reflectionFunction->getTentativeReturnType());
    }

    /**
     * @covers ::hasReturnType
     */
    public function testHasReturnType(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertTrue($reflectionFunction->hasReturnType());
    }

    /**
     * @covers ::inNamespace
     */
    public function testInNameSpace(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->inNamespace());
    }

    /**
     * @covers ::isClosure
     */
    public function testIsClosure(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->isClosure());
    }

    /**
     * @covers ::isDeprecated
     */
    public function testIsDeprecated(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->isDeprecated());
    }

    /**
     * @covers ::isGenerator
     */
    public function testIsGenerator(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->isGenerator());
    }

    /**
     * @covers ::isInternal
     */
    public function testIsInternal(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->isInternal());
    }

    /**
     * @covers ::isUserDefined
     */
    public function testIsUserDefined(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertTrue($reflectionFunction->isUserDefined());
    }

    /**
     * @covers ::isVariadic
     */
    public function testIsVariadic(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->isVariadic());
    }

    /**
     * @covers ::returnsReference
     */
    public function testReturnsReference(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertFalse($reflectionFunction->returnsReference());
    }

    /**
     * @covers ::getClosure
     */
    public function testGetClosure(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        assert($reflectionFunction->getClosure() instanceof Closure);

        $this->assertEquals('example', $reflectionFunction->getClosure()->__invoke('example'));
    }

    /**
     * @covers ::getParameters
     */
    public function testGetParameters(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals(new ReflectionParameter('example', 'param'), $reflectionFunction->getParameters()[0]);
    }

    /**
     * @covers ::invoke
     */
    public function testInvoke(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('example', $reflectionFunction->invoke('example'));
    }

    /**
     * @covers ::invokeArgs
     */
    public function testInvokeArgs(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $this->assertEquals('example', $reflectionFunction->invokeArgs(['example']));
    }

    /**
     * @covers ::invokeArgs
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
     * @covers ::__debugInfo
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
     * @covers ::__toString
     */
    public function testToString(): void
    {
        $reflectionFunction = new ReflectionFunction('example');

        $filename = realpath(self::FILENAME);

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
