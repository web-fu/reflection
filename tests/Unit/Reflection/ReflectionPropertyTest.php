<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\ReflectionProperty;
use WebFu\Reflection\ReflectionType;
use WebFu\Reflection\ReflectionTypeExtended;
use WebFu\Reflection\WrongPhpVersionException;
use WebFu\Tests\Fixtures\Attribute;
use WebFu\Tests\Fixtures\ClassWithDocComments;
use WebFu\Tests\Fixtures\ClassWithProperties;
use WebFu\Tests\Fixtures\ClassWithReadOnly;
use WebFu\Tests\Fixtures\ClassWithTypes;
use WebFu\Tests\Fixtures\GenericClass;

class ReflectionPropertyTest extends TestCase
{
    /**
     * @covers \WebFu\Reflection\ReflectionProperty::getAnnotations
     */
    public function testGetAnnotation(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'property');

        $this->assertEquals([
            '@depends-annotations Test',
            '@var class-string',
        ], $reflectionProperty->getAnnotations());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::getAttributes
     */
    public function testGetAttributes(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'propertyWithAttribute');
        $attributes = $reflectionProperty->getAttributes();

        $this->assertCount(1, $attributes);
        $this->assertEquals(Attribute::class, $attributes[0]->getName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::getDeclaringClass
     */
    public function testGetDeclaringClass(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(new ReflectionClass(ClassWithProperties::class), $reflectionProperty->getDeclaringClass());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::getDefaultValue
     */
    public function testGetDefaultValue(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(1, $reflectionProperty->getDefaultValue());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::getDocComment
     */
    public function testGetDocComment(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'propertyWithDocComment');

        $this->assertEquals('/**
     * @depends-annotations Test
     * @var class-string
     */', $reflectionProperty->getDocComment());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::getType
     */
    public function testGetType(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(new ReflectionType(['int']), $reflectionProperty->getType());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::getTypeNames
     */
    public function testGetTypeNames(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithTypes::class, 'simple');

        $this->assertEquals(['int'], $reflectionProperty->getTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithTypes::class, 'union');

        $this->assertEquals(['string', 'int'], $reflectionProperty->getTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithTypes::class, 'noType');

        $this->assertEquals(['mixed'], $reflectionProperty->getTypeNames());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::getDocTypeNames
     */
    public function testGetDocTypeName(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'property');

        $this->assertEquals(['class-string'], $reflectionProperty->getDocTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'noDocComments');

        $this->assertEquals([], $reflectionProperty->getDocTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'useStatementDocComment');

        $this->assertEquals([GenericClass::class . '[]'], $reflectionProperty->getDocTypeNames());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::getTypeExtended
     */
    public function testGetTypeExtended(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'propertyWithDocComment');

        $this->assertEquals(new ReflectionTypeExtended(['string'], ['class-string']), $reflectionProperty->getTypeExtended());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::getModifiers
     */
    public function testGetModifiers(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(ReflectionProperty::IS_PUBLIC, $reflectionProperty->getModifiers());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::getName
     */
    public function testGetName(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals('public', $reflectionProperty->getName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::getValue
     */
    public function testGetValue(): void
    {
        $object = new ClassWithProperties();
        $reflectionProperty = new ReflectionProperty($object, 'public');

        $this->assertEquals(1, $reflectionProperty->getValue($object));
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::hasDefaultValue
     */
    public function testHasDefaultValue(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertTrue($reflectionProperty->hasDefaultValue());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::hasType
     */
    public function testHasType(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(true, $reflectionProperty->hasType());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::isDefault
     */
    public function testIsDefault(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(true, $reflectionProperty->isDefault());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::isInitialized
     */
    public function testIsInitialized(): void
    {
        $object = new ClassWithProperties();
        $reflectionProperty = new ReflectionProperty($object, 'public');

        $this->assertTrue($reflectionProperty->isInitialized($object));
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::isPrivate
     */
    public function testIsPrivate(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'private');

        $this->assertTrue($reflectionProperty->isPrivate());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::isPromoted
     */
    public function testIsPromoted(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertFalse($reflectionProperty->isPromoted());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::isProtected
     */
    public function testIsProtected(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'protected');

        $this->assertTrue($reflectionProperty->isProtected());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::isPublic
     */
    public function testIsPublic(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertTrue($reflectionProperty->isPublic());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::isReadOnly
     */
    public function testIsReadOnly(): void
    {
        if (PHP_VERSION_ID < 80100) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('isReadOnly() is not available for PHP versions lower than 8.1.0');

            $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');
            $reflectionProperty->isReadOnly();

            $this->markTestSkipped('isReadOnly() is not available for PHP versions lower than 8.1.0');
        }

        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');
        $this->assertFalse($reflectionProperty->isReadOnly());

        $reflectionProperty = new ReflectionProperty(ClassWithReadOnly::class, 'public');
        $this->assertTrue($reflectionProperty->isReadOnly());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::isStatic
     */
    public function testIsStatic(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'staticPublic');

        $this->assertTrue($reflectionProperty->isStatic());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::setAccessible
     */
    public function testSetAccessible(): void
    {
        $object = new ClassWithProperties();
        $reflectionProperty = new ReflectionProperty($object, 'private');
        $reflectionProperty->setAccessible(true);

        $this->assertEquals(3, $reflectionProperty->getValue($object));
    }

    /**
     * @covers \WebFu\Reflection\ReflectionProperty::setValue
     */
    public function testSetValue(): void
    {
        $object = new ClassWithProperties();
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'private');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, 6);

        $this->assertEquals(6, $reflectionProperty->getValue($object));
    }
}
