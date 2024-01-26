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

use ArrayAccess;
use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionMethod;
use WebFu\Reflection\ReflectionPhpDocType;
use WebFu\Reflection\ReflectionType;
use WebFu\Reflection\WrongPhpVersionException;
use WebFu\Tests\Fixtures\AbstractClass;
use WebFu\Tests\Fixtures\ClassFinal;
use WebFu\Tests\Fixtures\ClassWithDocComments;
use WebFu\Tests\Fixtures\ClassWithFinals;
use WebFu\Tests\Fixtures\ClassWithMethods;
use WebFu\Tests\Fixtures\GenericClass;

/**
 * @covers \WebFu\Reflection\ReflectionMethod
 */
class ReflectionMethodTest extends TestCase
{
    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getAttributes
     */
    public function testGetAttributes(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');

        $this->assertEquals([], $reflectionMethod->getAttributes());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getClosureScopeClass
     */
    public function testGetClosureScopeClass(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');

        $this->assertNull($reflectionMethod->getClosureScopeClass());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getClosureThis
     */
    public function testGetClosureThis(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');

        $this->assertNull($reflectionMethod->getClosureThis());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getClosureUsedVariables
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
     * @covers \WebFu\Reflection\ReflectionMethod::getDocComment
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
     * @covers \WebFu\Reflection\ReflectionMethod::getEndLine
     */
    public function testGetEndLine(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(33, $reflectionMethod->getEndLine());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getExtension
     */
    public function testGetExtension(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertNull($reflectionMethod->getExtension());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getExtensionName
     */
    public function testGetExtensionName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertNull($reflectionMethod->getExtensionName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getFileName
     */
    public function testGetFileName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertStringContainsString('/Fixtures/ClassWithDocComments.php', $reflectionMethod->getFileName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getName
     */
    public function testGetName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals('getProperty', $reflectionMethod->getName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getNamespaceName
     */
    public function testGetNamespaceName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals('', $reflectionMethod->getNamespaceName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getShortName
     */
    public function getShortName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals('getProperty', $reflectionMethod->getShortName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getStartLine
     */
    public function testGetStartLine(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(30, $reflectionMethod->getStartLine());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getStaticVariables
     */
    public function testGetStaticVariables(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals([], $reflectionMethod->getStaticVariables());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getTentativeReturnType
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
     * @covers \WebFu\Reflection\ReflectionMethod::hasReturnType
     */
    public function testHasReturnType(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertTrue($reflectionMethod->hasReturnType());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::inNamespace
     */
    public function testInNamespace(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->inNamespace());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::isClosure
     */
    public function testIsClosure(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->isClosure());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::isDeprecated
     */
    public function testIsDeprecated(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->isDeprecated());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::isGenerator
     */
    public function testIsGenerator(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->isGenerator());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::isInternal
     */
    public function testIsInternal(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->isInternal());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::isUserDefined
     */
    public function testIsUserDefined(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertTrue($reflectionMethod->isUserDefined());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::isVariadic
     */
    public function testIsVariadic(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->isVariadic());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::returnsReference
     */
    public function testReturnsReference(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->returnsReference());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getAnnotations
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
     * @covers \WebFu\Reflection\ReflectionMethod::getNumberOfParameters
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
     * @covers \WebFu\Reflection\ReflectionMethod::getNumberOfRequiredParameters
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
     * @covers \WebFu\Reflection\ReflectionMethod::getParameters
     */
    public function testGetParameters(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');
        $parameters       = $reflectionMethod->getParameters();

        $this->assertEquals(new ReflectionType(['int']), $parameters[0]->getType());
        $this->assertEquals(new ReflectionType(['string']), $parameters[1]->getType());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getReturnType
     */
    public function testGetReturnType(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(new ReflectionType(types: ['string'], phpDocTypeNames: ['class-string']), $reflectionMethod->getReturnType());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getPhpDocReturnTypeNames
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
     * @covers \WebFu\Reflection\ReflectionMethod::getReturnPhpDocType
     */
    public function testGetReturnPhpDocType(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(new ReflectionPhpDocType(['string'], ['class-string']), $reflectionMethod->getReturnPhpDocType());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::getPrototype
     */
    public function testGetPrototype(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassFinal::class, 'publicFunction');

        $this->assertEquals(new ReflectionMethod(AbstractClass::class, 'publicFunction'), $reflectionMethod->getPrototype());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::hasPrototype
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
     * @covers \WebFu\Reflection\ReflectionMethod::invoke
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
     * @covers \WebFu\Reflection\ReflectionMethod::invokeArgs
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
     * @covers \WebFu\Reflection\ReflectionMethod::isAbstract
     */
    public function testIsAbstract(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isAbstract());

        $reflectionMethod = new ReflectionMethod(AbstractClass::class, 'publicFunction');
        $this->assertTrue($reflectionMethod->isAbstract());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::isConstructor
     */
    public function testIsConstructor(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isConstructor());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, '__construct');
        $this->assertTrue($reflectionMethod->isConstructor());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::isDestructor
     */
    public function testIsDestructor(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isDestructor());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, '__destruct');
        $this->assertTrue($reflectionMethod->isDestructor());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::isFinal
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
     * @covers \WebFu\Reflection\ReflectionMethod::isPrivate
     */
    public function testIsPrivate(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isPrivate());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'privateMethod');
        $this->assertTrue($reflectionMethod->isPrivate());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::isProtected
     */
    public function testIsProtected(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isProtected());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'protectedMethod');
        $this->assertTrue($reflectionMethod->isProtected());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::isPublic
     */
    public function testIsPublic(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertTrue($reflectionMethod->isPublic());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'privateMethod');
        $this->assertFalse($reflectionMethod->isPublic());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::isStatic
     */
    public function testIsStatic(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isStatic());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'staticMethod');
        $this->assertTrue($reflectionMethod->isStatic());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionMethod::__debugInfo
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
}
