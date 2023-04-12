<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\ReflectionException;
use WebFu\Reflection\ReflectionMethod;
use WebFu\Reflection\ReflectionProperty;
use WebFu\Reflection\ReflectionUseStatement;
use WebFu\Tests\Fixtures\ClassFinal;
use WebFu\Tests\Fixtures\ClassNonClonable;
use WebFu\Tests\Fixtures\ClassReadonly;
use WebFu\Tests\Fixtures\ClassWithAttributes;
use WebFu\Tests\Fixtures\ClassWithConstants;
use WebFu\Tests\Fixtures\ClassWithDocComments;
use WebFu\Tests\Fixtures\ClassWithInterfaces;
use WebFu\Tests\Fixtures\ClassWithMethods;
use WebFu\Tests\Fixtures\ClassWithProperties;
use WebFu\Tests\Fixtures\ClassWithUseStatements;
use WebFu\Tests\Fixtures\EnumClass;
use WebFu\Tests\Fixtures\GenericClass;
use WebFu\Tests\Fixtures\GenericInterface;
use WebFu\Tests\Fixtures\GenericTrait;
use WebFu\Tests\Fixtures\AbstractClass;

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
            'PUBLIC_WITH_ATTRIBUTE' => 4,
            'PUBLIC_WITH_DOC_COMMENT' => 5,
        ], $reflectionClass->getConstants());
    }

    public function testGetConstructor(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithMethods::class);

        $this->assertEquals(new ReflectionMethod(ClassWithMethods::class, '__construct'), $reflectionClass->getConstructor());

        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertNull($reflectionClass->getConstructor());
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

        $reflectionClass = new ReflectionClass(AbstractClass::class);

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

        $reflectionClass = new ReflectionClass(ClassFinal::class);

        $this->assertEquals(new ReflectionClass(AbstractClass::class), $reflectionClass->getParentClass());
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
        $reflectionClass = new ReflectionClass(ClassFinal::class);

        $this->assertEquals(
            [
            'traitFunction' => GenericTrait::class . '::publicTraitFunction',
        ], $reflectionClass->getTraitAliases());
    }

    public function testGetTraitNames(): void
    {
        $reflectionClass = new ReflectionClass(ClassFinal::class);

        $this->assertEquals([
            GenericTrait::class,
        ], $reflectionClass->getTraitNames());
    }

    public function testGetTraits(): void
    {
        $reflectionClass = new ReflectionClass(ClassFinal::class);

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

    public function testHasMethod(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithMethods::class);

        $this->assertTrue($reflectionClass->hasMethod('methodWithoutParameters'));
        $this->assertFalse($reflectionClass->hasMethod('doesNotExist'));
    }



    public function testHasProperty(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $this->assertTrue($reflectionClass->hasProperty('public'));
        $this->assertFalse($reflectionClass->hasProperty('doesNotExist'));
    }

    public function testImplementsInterface(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithInterfaces::class);

        $this->assertTrue($reflectionClass->implementsInterface(GenericInterface::class));
    }

    public function testInNamespace(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertTrue($reflectionClass->inNamespace());
    }

    public function testIsAbstract(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isAbstract());

        $reflectionClass = new ReflectionClass(AbstractClass::class);

        $this->assertTrue($reflectionClass->isAbstract());
    }

    public function testIsAnonymous(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isAnonymous());

        $reflectionClass = new ReflectionClass(new class() {});

        $this->assertTrue($reflectionClass->isAnonymous());
    }

    public function testIsCloneable(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertTrue($reflectionClass->isCloneable());

        $reflectionClass = new ReflectionClass(ClassNonClonable::class);

        $this->assertFalse($reflectionClass->isCloneable());
    }

    public function testIsEnum(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isEnum());

        if (PHP_VERSION_ID < 80100) {
            $this->markTestSkipped('Enum is only available in PHP 8.1+');
        }

        $reflectionClass = new ReflectionClass(EnumClass::class);

        $this->assertTrue($reflectionClass->isEnum());
    }

    public function testIsFinal(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isFinal());

        $reflectionClass = new ReflectionClass(ClassFinal::class);

        $this->assertTrue($reflectionClass->isFinal());
    }

    public function testIsInstance(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertTrue($reflectionClass->isInstance(new GenericClass()));
        $this->assertFalse($reflectionClass->isInstance(new \stdClass()));
    }

    public function testIsInstantiable(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertTrue($reflectionClass->isInstantiable());

        $reflectionClass = new ReflectionClass(AbstractClass::class);

        $this->assertFalse($reflectionClass->isInstantiable());
    }

    public function testIsInterface(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isInterface());

        $reflectionClass = new ReflectionClass(GenericInterface::class);

        $this->assertTrue($reflectionClass->isInterface());
    }

    public function testIsInternal(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isInternal());

        $reflectionClass = new ReflectionClass(\DateTime::class);

        $this->assertTrue($reflectionClass->isInternal());
    }

    public function testIsIterable(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isIterable());

        $reflectionClass = new ReflectionClass(\ArrayObject::class);

        $this->assertTrue($reflectionClass->isIterable());
    }

    public function testIsReadonly(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isReadonly());

        if (PHP_VERSION_ID < 80200) {
            $this->markTestSkipped('Readonly is only available in PHP 8.2+');
        }

        $reflectionClass = new ReflectionClass(ClassReadonly::class);

        $this->assertTrue($reflectionClass->isReadonly());
    }

    public function testIsSubclassOf(): void
    {
        $reflectionClass = new ReflectionClass(ClassFinal::class);

        $this->assertTrue($reflectionClass->isSubclassOf(AbstractClass::class));
        $this->assertFalse($reflectionClass->isSubclassOf(\DateTime::class));
    }

    public function testIsTrait(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertFalse($reflectionClass->isTrait());

        $reflectionClass = new ReflectionClass(GenericTrait::class);

        $this->assertTrue($reflectionClass->isTrait());
    }

    public function testIsUserDefined(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertTrue($reflectionClass->isUserDefined());

        $reflectionClass = new ReflectionClass(\DateTime::class);

        $this->assertFalse($reflectionClass->isUserDefined());
    }

    public function testNewInstance(): void
    {
        $reflectionClass = new ReflectionClass(GenericClass::class);

        $this->assertInstanceOf(GenericClass::class, $reflectionClass->newInstance());
    }

    public function testNewInstanceArgs(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithMethods::class);

        $this->assertInstanceOf(ClassWithMethods::class, $reflectionClass->newInstanceArgs([1, 'foo']));
    }

    public function testNewInstanceWithoutConstructor(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithMethods::class);

        $this->assertInstanceOf(ClassWithMethods::class, $reflectionClass->newInstanceWithoutConstructor());
    }

    public function testSetStaticPropertyValue(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithProperties::class);

        $reflectionClass->setStaticPropertyValue('staticPublic', 2);
        $this->assertEquals(2, ClassWithProperties::$staticPublic);
    }
}
