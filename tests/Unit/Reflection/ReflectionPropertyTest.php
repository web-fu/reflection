<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\ReflectionProperty;
use WebFu\Reflection\ReflectionType;
use WebFu\Reflection\ReflectionTypeExtended;
use WebFu\Tests\Fixtures\Attribute;
use WebFu\Tests\Fixtures\ClassWithDocComments;
use WebFu\Tests\Fixtures\ClassWithProperties;
use WebFu\Tests\Fixtures\ClassWithTypes;
use WebFu\Tests\Fixtures\GenericClass;

class ReflectionPropertyTest extends TestCase
{
    public function testGetAnnotation(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'property');

        $this->assertEquals([
            '@depends-annotations Test',
            '@var class-string',
        ], $reflectionProperty->getAnnotations());
    }

    public function testGetAttributes(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'propertyWithAttribute');
        $attributes = $reflectionProperty->getAttributes();

        $this->assertCount(1, $attributes);
        $this->assertEquals(Attribute::class, $attributes[0]->getName());
    }

    public function testGetDeclaringClass(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(new ReflectionClass(ClassWithProperties::class), $reflectionProperty->getDeclaringClass());
    }

    public function testGetDefaultValue(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(1, $reflectionProperty->getDefaultValue());
    }

    public function testGetDocComment(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'propertyWithDocComment');

        $this->assertEquals('/**
     * @depends-annotations Test
     * @var class-string
     */', $reflectionProperty->getDocComment());
    }

    public function testGetType(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(new ReflectionType(['int']), $reflectionProperty->getType());
    }

    public function testGetTypeNames(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithTypes::class, 'simple');

        $this->assertEquals(['int'], $reflectionProperty->getTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithTypes::class, 'union');

        $this->assertEquals(['string', 'int'], $reflectionProperty->getTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithTypes::class, 'noType');

        $this->assertEquals(['mixed'], $reflectionProperty->getTypeNames());
    }

    public function testGetDocTypeName(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'property');

        $this->assertEquals(['class-string'], $reflectionProperty->getDocTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'noDocComments');

        $this->assertEquals([], $reflectionProperty->getDocTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'useStatementDocComment');

        $this->assertEquals([GenericClass::class . '[]'], $reflectionProperty->getDocTypeNames());
    }

    public function testGetTypeExtended(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'propertyWithDocComment');

        $this->assertEquals(new ReflectionTypeExtended(['string'], ['class-string']), $reflectionProperty->getTypeExtended());
    }

    public function testGetModifiers(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(ReflectionProperty::IS_PUBLIC, $reflectionProperty->getModifiers());
    }

    public function testGetName(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals('public', $reflectionProperty->getName());
    }

    public function testGetValue(): void
    {
        $object = new ClassWithProperties();
        $reflectionProperty = new ReflectionProperty($object, 'public');

        $this->assertEquals(1, $reflectionProperty->getValue($object));
    }

    public function testHasDefaultValue(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertTrue($reflectionProperty->hasDefaultValue());
    }

    public function testHasType(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(true, $reflectionProperty->hasType());
    }

    public function testIsDefault(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(true, $reflectionProperty->isDefault());
    }

    public function testIsInitialized(): void
    {
        $object = new ClassWithProperties();
        $reflectionProperty = new ReflectionProperty($object, 'public');

        $this->assertTrue($reflectionProperty->isInitialized($object));
    }

    public function testIsPrivate(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'private');

        $this->assertTrue($reflectionProperty->isPrivate());
    }

    public function testIsPromoted(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertFalse($reflectionProperty->isPromoted());
    }

    public function testIsProtected(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'protected');

        $this->assertTrue($reflectionProperty->isProtected());
    }

    public function testIsPublic(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertTrue($reflectionProperty->isPublic());
    }

    public function testIsReadonly(): void
    {
        if (PHP_VERSION_ID < 80100) {
            $this->markTestSkipped('Readonly properties are only available in PHP 8.1 and above.');
        }

        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'property');

        $this->assertEquals(false, $reflectionProperty->isReadonly());
    }

    public function testIsStatic(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'staticPublic');

        $this->assertTrue($reflectionProperty->isStatic());
    }

    public function testSetAccessible(): void
    {
        $object = new ClassWithProperties();
        $reflectionProperty = new ReflectionProperty($object, 'private');
        $reflectionProperty->setAccessible(true);

        $this->assertEquals(3, $reflectionProperty->getValue($object));
    }

    public function testSetValue(): void
    {
        $object = new ClassWithProperties();
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'private');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, 6);

        $this->assertEquals(6, $reflectionProperty->getValue($object));
    }
}
