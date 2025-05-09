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

use ArrayAccess;
use Closure;
use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionException;
use WebFu\Reflection\ReflectionMethod;
use WebFu\Reflection\ReflectionType;
use WebFu\Reflection\Tests\data\AbstractClass;
use WebFu\Reflection\Tests\data\ClassFinal;
use WebFu\Reflection\Tests\data\ClassWithDocComments;
use WebFu\Reflection\Tests\data\ClassWithFinals;
use WebFu\Reflection\Tests\data\ClassWithMethods;
use WebFu\Reflection\Tests\data\GenericClass;
use WebFu\Reflection\WrongPhpVersionException;

/**
 * @group unit
 *
 * @coversDefaultClass  \WebFu\Reflection\ReflectionMethod
 */
class ReflectionMethodTest extends TestCase
{
    /**
     * @covers ::getAttributes
     */
    public function testGetAttributes(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');

        $this->assertEquals([], $reflectionMethod->getAttributes());
    }

    /**
     * @covers ::getClosure
     */
    public function testGetClosure(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');
        $object           = new ClassWithMethods();

        $this->assertInstanceOf(Closure::class, $reflectionMethod->getClosure($object));

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'staticMethod');

        $this->assertInstanceOf(Closure::class, $reflectionMethod->getClosure());

        $this->expectException(ReflectionException::class);
        $this->expectExceptionMessage('Cannot create closure for method without object');

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $reflectionMethod->getClosure();

        $this->expectException(ReflectionException::class);
        $this->expectExceptionMessage('Cannot bind a static closure to an instance');

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'staticMethod');
        $reflectionMethod->getClosure(new ClassWithMethods());
    }

    /**
     * @covers ::getClosureScopeClass
     */
    public function testGetClosureScopeClass(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');

        $this->assertNull($reflectionMethod->getClosureScopeClass());
    }

    /**
     * @covers ::getClosureThis
     */
    public function testGetClosureThis(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');

        $this->assertNull($reflectionMethod->getClosureThis());
    }

    /**
     * @covers ::getClosureUsedVariables
     */
    public function testGetClosureUsedVariables(): void
    {
        if (PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Closures are not available for PHP versions lower than 8.1.0');
        }

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');

        $this->assertEquals([], $reflectionMethod->getClosureUsedVariables());
    }

    /**
     * @covers ::getDocComment
     */
    public function testGetDocComment(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals('/**
     * @depends-annotations Test
     * @return class-string
     */', $reflectionMethod->getDocComment());
    }

    /**
     * @covers ::getEndLine
     */
    public function testGetEndLine(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(33, $reflectionMethod->getEndLine());
    }

    /**
     * @covers ::getExtension
     */
    public function testGetExtension(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertNull($reflectionMethod->getExtension());
    }

    /**
     * @covers ::getExtensionName
     */
    public function testGetExtensionName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertNull($reflectionMethod->getExtensionName());
    }

    /**
     * @covers ::getFileName
     */
    public function testGetFileName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertStringContainsString('/data/ClassWithDocComments.php', $reflectionMethod->getFileName());
    }

    /**
     * @covers ::getName
     */
    public function testGetName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals('getProperty', $reflectionMethod->getName());
    }

    /**
     * @covers ::getNamespaceName
     */
    public function testGetNamespaceName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals('', $reflectionMethod->getNamespaceName());
    }

    /**
     * @covers ::getShortName
     */
    public function getShortName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals('getProperty', $reflectionMethod->getShortName());
    }

    /**
     * @covers ::getStartLine
     */
    public function testGetStartLine(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(30, $reflectionMethod->getStartLine());
    }

    /**
     * @covers ::getStaticVariables
     */
    public function testGetStaticVariables(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals([], $reflectionMethod->getStaticVariables());
    }

    /**
     * @covers ::getTentativeReturnType
     */
    public function testGetTentativeReturnType(): void
    {
        if (PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Tentative return types are not available for PHP versions lower than 8.1.0');
        }

        $reflectionMethod = new ReflectionMethod(ArrayAccess::class, 'offsetGet');
        $actual           = $reflectionMethod->getTentativeReturnType();

        $this->assertEquals(new ReflectionType(['mixed']), $actual);
    }

    /**
     * @covers ::hasReturnType
     */
    public function testHasReturnType(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertTrue($reflectionMethod->hasReturnType());
    }

    /**
     * @covers ::inNamespace
     */
    public function testInNamespace(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->inNamespace());
    }

    /**
     * @covers ::isClosure
     */
    public function testIsClosure(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->isClosure());
    }

    /**
     * @covers ::isDeprecated
     */
    public function testIsDeprecated(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->isDeprecated());
    }

    /**
     * @covers ::isGenerator
     */
    public function testIsGenerator(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->isGenerator());
    }

    /**
     * @covers ::isInternal
     */
    public function testIsInternal(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->isInternal());
    }

    /**
     * @covers ::isUserDefined
     */
    public function testIsUserDefined(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertTrue($reflectionMethod->isUserDefined());
    }

    /**
     * @covers ::isVariadic
     */
    public function testIsVariadic(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->isVariadic());
    }

    /**
     * @covers ::returnsReference
     */
    public function testReturnsReference(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->returnsReference());
    }

    /**
     * @covers ::getAnnotations
     */
    public function testGetAnnotation(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals([
            '@depends-annotations Test',
            '@return class-string',
        ], $reflectionMethod->getAnnotations());
    }

    /**
     * @covers ::getNumberOfParameters
     */
    public function testGetNumberOfParameters(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertEquals(0, $reflectionMethod->getNumberOfParameters());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithAllMandatoryParameters');
        $this->assertEquals(2, $reflectionMethod->getNumberOfParameters());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithAllDefaultParameters');
        $this->assertEquals(2, $reflectionMethod->getNumberOfParameters());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');
        $this->assertEquals(2, $reflectionMethod->getNumberOfParameters());
    }

    /**
     * @covers ::getNumberOfRequiredParameters
     */
    public function testGetNumberOfRequiredParameters(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertEquals(0, $reflectionMethod->getNumberOfRequiredParameters());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithAllMandatoryParameters');
        $this->assertEquals(2, $reflectionMethod->getNumberOfRequiredParameters());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithAllDefaultParameters');
        $this->assertEquals(0, $reflectionMethod->getNumberOfRequiredParameters());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');
        $this->assertEquals(1, $reflectionMethod->getNumberOfRequiredParameters());
    }

    /**
     * @covers ::getParameters
     */
    public function testGetParameters(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');
        $parameters       = $reflectionMethod->getParameters();

        $this->assertEquals(new ReflectionType(['int']), $parameters[0]->getType());
        $this->assertEquals(new ReflectionType(['string']), $parameters[1]->getType());
    }

    /**
     * @covers ::getReturnType
     */
    public function testGetReturnType(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(new ReflectionType(types: ['string'], phpDocTypeNames: ['class-string']), $reflectionMethod->getReturnType());
    }

    /**
     * @covers ::getPhpDocReturnTypeNames
     */
    public function testGetReturnDocTypeName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(['class-string'], $reflectionMethod->getPhpDocReturnTypeNames());

        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'noDocComments');

        $this->assertEquals([], $reflectionMethod->getPhpDocReturnTypeNames());

        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getUseStatementDocComment');

        $this->assertEquals([GenericClass::class.'[]'], $reflectionMethod->getPhpDocReturnTypeNames());
    }

    /**
     * @covers ::getPrototype
     */
    public function testGetPrototype(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassFinal::class, 'publicFunction');

        $this->assertEquals(new ReflectionMethod(AbstractClass::class, 'publicFunction'), $reflectionMethod->getPrototype());
    }

    /**
     * @covers ::hasPrototype
     */
    public function testHasPrototype(): void
    {
        if (PHP_VERSION_ID < 80200) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('hasPrototype() is not available for PHP versions lower than 8.2.0');

            $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
            $reflectionMethod->hasPrototype();

            $this->markTestSkipped('hasPrototype() is not available for PHP versions lower than 8.2.0');
        }

        $reflectionMethod = new ReflectionMethod(ClassFinal::class, 'publicFunction');
        $this->assertTrue($reflectionMethod->hasPrototype());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->hasPrototype());
    }

    /**
     * @covers ::invoke
     */
    public function testInvoke(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithAllMandatoryParameters');

        $this->assertEquals([
            'param1' => 1,
            'param2' => 'string',
        ], $reflectionMethod->invoke(new ClassWithMethods(), 1, 'string'));
    }

    /**
     * @covers ::invokeArgs
     */
    public function testInvokeArgs(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithAllMandatoryParameters');

        $this->assertEquals([
            'param1' => 1,
            'param2' => 'string',
        ], $reflectionMethod->invokeArgs(new ClassWithMethods(), [1, 'string']));
    }

    /**
     * @covers ::isAbstract
     */
    public function testIsAbstract(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isAbstract());

        $reflectionMethod = new ReflectionMethod(AbstractClass::class, 'publicFunction');
        $this->assertTrue($reflectionMethod->isAbstract());
    }

    /**
     * @covers ::isConstructor
     */
    public function testIsConstructor(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isConstructor());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, '__construct');
        $this->assertTrue($reflectionMethod->isConstructor());
    }

    /**
     * @covers ::isDestructor
     */
    public function testIsDestructor(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isDestructor());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, '__destruct');
        $this->assertTrue($reflectionMethod->isDestructor());
    }

    /**
     * @covers ::isFinal
     */
    public function testIsFinal(): void
    {
        if (PHP_VERSION_ID < 80100) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('isFinal is not available for PHP versions lower than 8.1.0');

            $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
            $reflectionMethod->isFinal();

            $this->markTestSkipped('Final keyword is not available for PHP versions lower than 8.1.0');
        }

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isFinal());

        $reflectionMethod = new ReflectionMethod(ClassWithFinals::class, 'finalMethod');
        $this->assertTrue($reflectionMethod->isFinal());
    }

    /**
     * @covers ::isPrivate
     */
    public function testIsPrivate(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isPrivate());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'privateMethod');
        $this->assertTrue($reflectionMethod->isPrivate());
    }

    /**
     * @covers ::isProtected
     */
    public function testIsProtected(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isProtected());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'protectedMethod');
        $this->assertTrue($reflectionMethod->isProtected());
    }

    /**
     * @covers ::isPublic
     */
    public function testIsPublic(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertTrue($reflectionMethod->isPublic());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'privateMethod');
        $this->assertFalse($reflectionMethod->isPublic());
    }

    /**
     * @covers ::isStatic
     */
    public function testIsStatic(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isStatic());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'staticMethod');
        $this->assertTrue($reflectionMethod->isStatic());
    }

    /**
     * @covers ::__debugInfo
     */
    public function testDebugInfo(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $actual           = $reflectionMethod->__debugInfo();

        $this->assertEquals([
            'name'        => 'methodWithoutParameters',
            'class'       => ClassWithMethods::class,
            'attributes'  => [],
            'annotations' => [],
        ], $actual);
    }

    /**
     * @covers ::__toString
     */
    public function testToString(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');

        $filename = realpath(__DIR__.'/data/ClassWithMethods.php');

        $expected = <<<EOT
            Method [ <user> public method methodWithoutParameters ] {
              @@ $filename 15 - 17

              - Parameters [0] {
              }
              - Return [ void ]
            }

            EOT;

        $this->assertEquals($expected, $reflectionMethod->__toString());
    }
}
