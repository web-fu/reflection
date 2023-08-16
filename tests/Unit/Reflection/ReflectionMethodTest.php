<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionMethod;
use WebFu\Reflection\ReflectionType;
use WebFu\Reflection\ReflectionTypeExtended;
use WebFu\Tests\Fixtures\AbstractClass;
use WebFu\Tests\Fixtures\ClassFinal;
use WebFu\Tests\Fixtures\ClassWithDocComments;
use WebFu\Tests\Fixtures\ClassWithMethods;
use WebFu\Tests\Fixtures\GenericClass;

class ReflectionMethodTest extends TestCase
{
    public function testGetAttributes(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');

        $this->assertEquals([], $reflectionMethod->getAttributes());
    }

    public function testGetClosureScopeClass(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');

        $this->assertEquals(null, $reflectionMethod->getClosureScopeClass());
    }

    public function testGetClosureThis(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');

        $this->assertEquals(null, $reflectionMethod->getClosureThis());
    }

    public function testGetClosureUsedVariables(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');

        $this->assertEquals([], $reflectionMethod->getClosureUsedVariables());
    }

    public function testGetDocComment(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals('/**
     * @depends-annotations Test
     * @return class-string
     */', $reflectionMethod->getDocComment());
    }

    public function testGetEndLine(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(33, $reflectionMethod->getEndLine());
    }

    public function testGetExtension(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(null, $reflectionMethod->getExtension());
    }

    public function testGetExtensionName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(null, $reflectionMethod->getExtensionName());
    }

    public function testGetFileName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertStringContainsString('reflection/tests/Fixtures/ClassWithDocComments.php', $reflectionMethod->getFileName());
    }

    public function testGetName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals('getProperty', $reflectionMethod->getName());
    }

    public function testGetNamespaceName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals('', $reflectionMethod->getNamespaceName());
    }

    public function getShortName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals('getProperty', $reflectionMethod->getShortName());
    }

    public function testGetStartLine(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(30, $reflectionMethod->getStartLine());
    }

    public function testGetStaticVariables(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals([], $reflectionMethod->getStaticVariables());
    }

    public function testGetTentativeReturnType(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(null, $reflectionMethod->getTentativeReturnType());
    }

    public function testHasReturnType(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(true, $reflectionMethod->hasReturnType());
    }

    public function testInNameSpace(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertFalse($reflectionMethod->inNamespace());
    }

    public function testIsClosure(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(false, $reflectionMethod->isClosure());
    }

    public function testIsDeprecated(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(false, $reflectionMethod->isDeprecated());
    }

    public function testIsGenerator(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(false, $reflectionMethod->isGenerator());
    }

    public function testIsInternal(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(false, $reflectionMethod->isInternal());
    }

    public function testIsUserDefined(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(true, $reflectionMethod->isUserDefined());
    }

    public function testIsVariadic(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(false, $reflectionMethod->isVariadic());
    }

    public function testReturnsReference(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(false, $reflectionMethod->returnsReference());
    }

    public function testGetAnnotation(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals([
            '@depends-annotations Test',
            '@return class-string',
        ], $reflectionMethod->getAnnotations());
    }

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

    public function testGetParameters(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters');
        $parameters = $reflectionMethod->getParameters();

        $this->assertEquals(new ReflectionType(['int']), $parameters[0]->getType());
        $this->assertEquals(new ReflectionType(['string']), $parameters[1]->getType());
    }

    public function testGetReturnType(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(new ReflectionType(['string']), $reflectionMethod->getReturnType());
    }

    public function testGetReturnDocTypeName(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(['class-string'], $reflectionMethod->getReturnDocTypeNames());

        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'noDocComments');

        $this->assertEquals([], $reflectionMethod->getReturnDocTypeNames());

        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getUseStatementDocComment');

        $this->assertEquals([GenericClass::class . '[]'], $reflectionMethod->getReturnDocTypeNames());
    }

    public function testGetReturnTypeExtended(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithDocComments::class, 'getProperty');

        $this->assertEquals(new ReflectionTypeExtended(['string'], ['class-string']), $reflectionMethod->getReturnTypeExtended());
    }

    public function testGetPrototype(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassFinal::class, 'publicFunction');

        $this->assertEquals(new ReflectionMethod(AbstractClass::class, 'publicFunction'), $reflectionMethod->getPrototype());
    }

    public function testHasPrototype(): void
    {
        if (PHP_VERSION_ID < 80200) {
            $this->markTestSkipped('PHP 8.2+ only');
        }

        $reflectionMethod = new ReflectionMethod(ClassFinal::class, 'publicFunction');
        $this->assertTrue($reflectionMethod->hasPrototype());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->hasPrototype());
    }

    public function testInvoke(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithAllMandatoryParameters');

        $this->assertEquals([
            'param1' => 1,
            'param2' => 'string',
        ], $reflectionMethod->invoke(new ClassWithMethods(), 1, 'string'));
    }

    public function testInvokeArgs(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithAllMandatoryParameters');

        $this->assertEquals([
            'param1' => 1,
            'param2' => 'string',
        ], $reflectionMethod->invokeArgs(new ClassWithMethods(), [1, 'string']));
    }

    public function testIsAbstract(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isAbstract());

        $reflectionMethod = new ReflectionMethod(AbstractClass::class, 'publicFunction');
        $this->assertTrue($reflectionMethod->isAbstract());
    }

    public function testIsConstructor(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isConstructor());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, '__construct');
        $this->assertTrue($reflectionMethod->isConstructor());
    }

    public function testIsDestructor(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isDestructor());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, '__destruct');
        $this->assertTrue($reflectionMethod->isDestructor());
    }

    public function testIsFinal(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isFinal());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'finalMethod');
        $this->assertTrue($reflectionMethod->isFinal());
    }

    public function testIsPrivate(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isPrivate());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'privateMethod');
        $this->assertTrue($reflectionMethod->isPrivate());
    }

    public function testIsProtected(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isProtected());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'protectedMethod');
        $this->assertTrue($reflectionMethod->isProtected());
    }

    public function testIsPublic(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertTrue($reflectionMethod->isPublic());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'privateMethod');
        $this->assertFalse($reflectionMethod->isPublic());
    }

    public function testMethodIsStatic(): void
    {
        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters');
        $this->assertFalse($reflectionMethod->isStatic());

        $reflectionMethod = new ReflectionMethod(ClassWithMethods::class, 'staticMethod');
        $this->assertTrue($reflectionMethod->isStatic());
    }
}
