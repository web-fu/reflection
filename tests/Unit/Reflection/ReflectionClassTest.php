<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\ReflectionException;
use WebFu\Reflection\ReflectionMethod;
use WebFu\Reflection\ReflectionProperty;
use WebFu\Reflection\ReflectionUseStatement;
use WebFu\Tests\Fixtures\ChildClass;
use WebFu\Tests\Fixtures\ClassWithAttributes;
use WebFu\Tests\Fixtures\ClassWithConstants;
use WebFu\Tests\Fixtures\ClassWithDocComments;
use WebFu\Tests\Fixtures\ClassWithMethods;
use WebFu\Tests\Fixtures\ClassWithProperties;
use WebFu\Tests\Fixtures\ClassWithUseStatements;
use WebFu\Tests\Fixtures\GenericClass;
use WebFu\Tests\Fixtures\GenericInterface;
use WebFu\Tests\Fixtures\GenericTrait;
use WebFu\Tests\Fixtures\ParentClass;

class ReflectionClassTest extends TestCase
{
    public function testGetAnnotation(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithDocComments::class);

        $this->assertEquals(['@template Test'], $reflectionClass->getAnnotations());
    }

    public function testGetAttributes(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithAttributes::class);
        $this->assertCount(1, $reflectionClass->getAttributes());
        $this->assertEquals('WebFu\Tests\Fixtures\Attribute', $reflectionClass->getAttributes()[0]->getName());
    }

    /**
     * @dataProvider constantProvider
     */
    public function testGetConstant(int $expected, string $name): void
    {
        $reflectionClass = new ReflectionClass(ClassWithConstants::class);
        $this->assertEquals($expected, $reflectionClass->getConstant($name));
    }

    /**
     * @return iterable<array{expected:int, name:string}>
     */
    public function constantProvider(): iterable
    {
        yield 'public' => [
            'expected' => 1,
            'name' => 'PUBLIC',
        ];
        yield 'protected' => [
            'expected' => 2,
            'name' => 'PROTECTED',
        ];
        yield 'private' => [
            'expected' => 3,
            'name' => 'PRIVATE',
        ];
    }

    public function testGetConstantFail(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithConstants::class);

        $this->expectException(ReflectionException::class);
        $this->expectExceptionMessage('Undefined constant name: FOO');

        $reflectionClass->getConstant('FOO');
    }

    public function testGetConstants(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithConstants::class);

        $this->assertEquals([
            'PUBLIC' => 1,
            'PROTECTED' => 2,
            'PRIVATE' => 3,
        ], $reflectionClass->getConstants());
    }

    public function testGetConstructor(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithMethods::class);

        $this->assertEquals(new ReflectionMethod(ClassWithMethods::class, '__construct'), $reflectionClass->getConstructor());
    }

    public function testGetDefaultProperties(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertEquals([
            'public' => 1,
            'protected' => 2,
            'private' => 3,
            'staticPublic' => 1,
            'staticProtected' => 2,
            'staticPrivate' => 3,
        ], $reflectionClass->getDefaultProperties());
    }

    public function testDocComment(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithDocComments::class);

        $this->assertEquals('/**' . PHP_EOL . ' * @template Test' . PHP_EOL . ' */', $reflectionClass->getDocComment());

        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertNull($reflectionClass->getDocComment());
    }

    public function testGetEndLine(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals(9, $reflectionClass->getEndLine());
    }

    public function testExtension(): void
    {
        $reflectionClass = new ReflectionClass(\ReflectionClass::class);

        $this->assertEquals(new \ReflectionExtension('Reflection'), $reflectionClass->getExtension());

        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertNull($reflectionClass->getExtension());
    }

    public function testExtensionName(): void
    {
        $reflectionClass = new ReflectionClass(\ReflectionClass::class);

        $this->assertEquals('Reflection', $reflectionClass->getExtensionName());

        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertNull($reflectionClass->getExtensionName());
    }

    public function testGetFileName(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals('/app/reflection/tests/Fixtures/GenericClass.php', $reflectionClass->getFileName());

        $reflectionClass = new ReflectionClass(\DateTime::class);

        $this->assertNull($reflectionClass->getFileName());
    }

    public function testGetInterfaceNames(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals([GenericInterface::class], $reflectionClass->getInterfaceNames());
    }

    public function testGetInterfaces(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals([GenericInterface::class => new ReflectionClass(GenericInterface::class)], $reflectionClass->getInterfaces());
    }

    public function testGetMethod(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithMethods::class);

        $this->assertEquals(new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters'), $reflectionClass->getMethod('methodWithoutParameters'));
    }

    public function testGetMethods(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithMethods::class);

        $this->assertEquals([
            new ReflectionMethod(ClassWithMethods::class, '__construct'),
            new ReflectionMethod(ClassWithMethods::class, 'methodWithoutParameters'),
            new ReflectionMethod(ClassWithMethods::class, 'methodWithAllMandatoryParameters'),
            new ReflectionMethod(ClassWithMethods::class, 'methodWithAllDefaultParameters'),
            new ReflectionMethod(ClassWithMethods::class, 'methodWithSomeDefaultParameters'),
        ], $reflectionClass->getMethods());
    }

    public function testGetModifiers(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals(0, $reflectionClass->getModifiers());

        $reflectionClass = new ReflectionClass(ParentClass::class);

        $this->assertEquals(\ReflectionClass::IS_EXPLICIT_ABSTRACT, $reflectionClass->getModifiers());
    }

    public function testGetName(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals(GenericClass::class, $reflectionClass->getName());
    }

    public function testGetNamespaceName(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals('WebFu\Tests\Fixtures', $reflectionClass->getNamespaceName());
    }

    public function testGetParentClass(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertNull($reflectionClass->getParentClass());

        $reflectionClass = new ReflectionClass(ChildClass::class);

        $this->assertEquals(new ReflectionClass(ParentClass::class), $reflectionClass->getParentClass());
    }

    public function testGetProperties(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertEquals([
            new ReflectionProperty(ClassWithProperties::class, 'public'),
            new ReflectionProperty(ClassWithProperties::class, 'protected'),
            new ReflectionProperty(ClassWithProperties::class, 'private'),
            new ReflectionProperty(ClassWithProperties::class, 'propertyWithoutDefault'),
            new ReflectionProperty(ClassWithProperties::class, 'staticPublic'),
            new ReflectionProperty(ClassWithProperties::class, 'staticProtected'),
            new ReflectionProperty(ClassWithProperties::class, 'staticPrivate'),
        ], $reflectionClass->getProperties());
    }

    /** @dataProvider propertyProvider */
    public function testGetProperty(string $name, ReflectionProperty|null $expected): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertEquals($expected, $reflectionClass->getProperty($name));
    }

    public function propertyProvider(): iterable
    {
        yield ['name' => 'public', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'public')];
        yield ['name' => 'protected', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'protected')];
        yield ['name' => 'private', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'private')];
        yield ['name' => 'propertyWithoutDefault', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'propertyWithoutDefault')];
        yield ['name' => 'staticPublic', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'staticPublic')];
        yield ['name' => 'staticProtected', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'staticProtected')];
        yield ['name' => 'staticPrivate', 'expected' => new ReflectionProperty(ClassWithProperties::class, 'staticPrivate')];
        yield ['name' => 'iDoNotExist', 'expected' => null];
    }

    public function testGetReflectionConstant(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithConstants::class);

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $this->assertEquals($expected, $reflectionClass->getReflectionConstant($name));
    }

    public function testGetReflectionConstants(): void
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetShortName(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals('GenericClass', $reflectionClass->getShortName());
    }

    public function testGetStartLine(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertEquals(7, $reflectionClass->getStartLine());
    }

    public function testGetStaticProperties(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertEquals([
            'staticPublic' => 1,
            'staticProtected' => 2,
            'staticPrivate' => 3,
        ], $reflectionClass->getStaticProperties());
    }

    public function testGetStaticPropertyValue(): void
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetTraitAliases(): void
    {
        $reflectionClass = new ReflectionClass(ChildClass::class);

        $this->assertEquals(
            [
            'traitFunction' => GenericTrait::class . '::publicTraitFunction',
        ], $reflectionClass->getTraitAliases());
    }

    public function testGetTraitNames(): void
    {
        $reflectionClass = new ReflectionClass(ChildClass::class);

        $this->assertEquals([
            GenericTrait::class,
        ], $reflectionClass->getTraitNames());
    }

    public function testGetTraits(): void
    {
        $reflectionClass = new ReflectionClass(ChildClass::class);

        $this->assertEquals([
            GenericTrait::class => new ReflectionClass(GenericTrait::class),
        ], $reflectionClass->getTraits());
    }

    public function testGetUseStatements(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithUseStatements::class);
        $this->assertEquals([
            new ReflectionUseStatement(GenericClass::class, GenericClass::class),
            new ReflectionUseStatement(\DateTime::class, 'DT'),
        ], $reflectionClass->getUseStatements());
    }

    public function testHasConstant(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithConstants::class);

        $this->assertTrue($reflectionClass->hasConstant('PUBLIC'));
        $this->assertFalse($reflectionClass->hasConstant('DOES_NOT_EXIST'));
    }

    public function testHasProperty(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertTrue($reflectionClass->hasProperty('public'));
        $this->assertFalse($reflectionClass->hasProperty('doesNotExist'));
    }
}
