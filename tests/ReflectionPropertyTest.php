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

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\ReflectionProperty;
use WebFu\Reflection\ReflectionType;
use WebFu\Reflection\Tests\data\Attribute;
use WebFu\Reflection\Tests\data\ClassWithAsymmetricVisibilityProperties;
use WebFu\Reflection\Tests\data\ClassWithDocComments;
use WebFu\Reflection\Tests\data\ClassWithProperties;
use WebFu\Reflection\Tests\data\ClassWithReadOnly;
use WebFu\Reflection\Tests\data\ClassWithTypes;
use WebFu\Reflection\Tests\data\GenericClass;
use WebFu\Reflection\WrongPhpVersionException;

/**
 * @group unit
 *
 * @coversDefaultClass  \WebFu\Reflection\ReflectionProperty
 */
class ReflectionPropertyTest extends TestCase
{
    /**
     * @covers ::getAnnotations
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
     * @covers ::getAttributes
     */
    public function testGetAttributes(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'propertyWithAttribute');
        $attributes         = $reflectionProperty->getAttributes();

        $this->assertCount(1, $attributes);
        $this->assertEquals(Attribute::class, $attributes[0]->getName());
    }

    /**
     * @covers ::getDeclaringClass
     */
    public function testGetDeclaringClass(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(new ReflectionClass(ClassWithProperties::class), $reflectionProperty->getDeclaringClass());
    }

    /**
     * @covers ::getDefaultValue
     */
    public function testGetDefaultValue(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(1, $reflectionProperty->getDefaultValue());
    }

    /**
     * @covers ::getDocComment
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
     * @covers ::getType
     */
    public function testGetType(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(new ReflectionType(['int']), $reflectionProperty->getType());
    }

    /**
     * @covers ::getTypeNames
     */
    public function testGetTypeNames(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithTypes::class, 'simple');

        $this->assertEquals(['int'], $reflectionProperty->getTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithTypes::class, 'union');

        $this->assertEquals(['int', 'string'], $reflectionProperty->getTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithTypes::class, 'noType');

        $this->assertEquals(['mixed'], $reflectionProperty->getTypeNames());
    }

    /**
     * @covers ::getPhpDocTypeNames
     */
    public function testGetDocTypeName(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'property');

        $this->assertEquals(['class-string'], $reflectionProperty->getPhpDocTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'noDocComments');

        $this->assertEquals([], $reflectionProperty->getPhpDocTypeNames());

        $reflectionProperty = new ReflectionProperty(ClassWithDocComments::class, 'useStatementDocComment');

        $this->assertEquals([GenericClass::class.'[]'], $reflectionProperty->getPhpDocTypeNames());
    }

    /**
     * @covers ::getModifiers
     */
    public function testGetModifiers(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals(ReflectionProperty::IS_PUBLIC, $reflectionProperty->getModifiers());
    }

    /**
     * @covers ::getName
     */
    public function testGetName(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertEquals('public', $reflectionProperty->getName());
    }

    /**
     * @covers ::getValue
     */
    public function testGetValue(): void
    {
        $object             = new ClassWithProperties();
        $reflectionProperty = new ReflectionProperty($object, 'public');

        $this->assertEquals(1, $reflectionProperty->getValue($object));
    }

    /**
     * @covers ::hasDefaultValue
     */
    public function testHasDefaultValue(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertTrue($reflectionProperty->hasDefaultValue());
    }

    /**
     * @covers ::hasType
     */
    public function testHasType(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertTrue($reflectionProperty->hasType());
    }

    /**
     * @covers ::isDefault
     */
    public function testIsDefault(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertTrue($reflectionProperty->isDefault());
    }

    /**
     * @covers ::isInitialized
     */
    public function testIsInitialized(): void
    {
        $object             = new ClassWithProperties();
        $reflectionProperty = new ReflectionProperty($object, 'public');

        $this->assertTrue($reflectionProperty->isInitialized($object));
    }

    /**
     * @covers ::isPrivate
     */
    public function testIsPrivate(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'private');

        $this->assertTrue($reflectionProperty->isPrivate());
    }

    /**
     * @covers ::isPromoted
     */
    public function testIsPromoted(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertFalse($reflectionProperty->isPromoted());
    }

    /**
     * @covers ::isProtected
     */
    public function testIsProtected(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'protected');

        $this->assertTrue($reflectionProperty->isProtected());
    }

    /**
     * @covers ::isPublic
     */
    public function testIsPublic(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');

        $this->assertTrue($reflectionProperty->isPublic());
    }

    /**
     * @covers ::isReadOnly
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
     * @covers ::isStatic
     */
    public function testIsStatic(): void
    {
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'staticPublic');

        $this->assertTrue($reflectionProperty->isStatic());
    }

    /**
     * @covers ::setAccessible
     */
    public function testSetAccessible(): void
    {
        $object             = new ClassWithProperties();
        $reflectionProperty = new ReflectionProperty($object, 'private');
        $reflectionProperty->setAccessible(true);

        $this->assertEquals(3, $reflectionProperty->getValue($object));
    }

    /**
     * @covers ::setValue
     */
    public function testSetValue(): void
    {
        $object             = new ClassWithProperties();
        $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'private');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, 6);

        $this->assertEquals(6, $reflectionProperty->getValue($object));
    }

    public function testCheckAsymmetricVisibility(): void
    {
        if (PHP_VERSION_ID < 80400) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('isProtectedSet() is not available for PHP versions lower than 8.4.0');

            $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');
            $reflectionProperty->isProtectedSet();

            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('isPrivateSet() is not available for PHP versions lower than 8.4.0');

            $reflectionProperty = new ReflectionProperty(ClassWithProperties::class, 'public');
            $reflectionProperty->isPrivateSet();

            $this->markTestSkipped('Asymmetric Visibility is not available for PHP versions lower than 8.4.0');
        }

        $reflectionProperty = new ReflectionProperty(ClassWithAsymmetricVisibilityProperties::class, 'version');

        $this->assertTrue($reflectionProperty->isPublic());
        $this->assertTrue($reflectionProperty->isPrivateSet());
        $this->assertFalse($reflectionProperty->isProtectedSet());
    }
}
