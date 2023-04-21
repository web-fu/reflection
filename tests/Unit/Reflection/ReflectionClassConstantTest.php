<?php

declare(strict_types=1);

namespace WebFu\Tests\Unit\Reflection;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\ReflectionClassConstant;
use WebFu\Tests\Fixtures\Attribute;
use WebFu\Tests\Fixtures\ClassWithConstants;
use WebFu\Tests\Fixtures\EnumClass;

class ReflectionClassConstantTest extends TestCase
{
    public function testGetAttributes(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC_WITH_ATTRIBUTE');
        $attributes = $reflectionClassConstant->getAttributes();

        $this->assertCount(1, $attributes);
        $this->assertSame(Attribute::class, $attributes[0]->getName());
    }

    public function testGetDeclaringClass(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertEquals(new ReflectionClass(ClassWithConstants::class), $reflectionClassConstant->getDeclaringClass());
    }

    public function testGetDocComment(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertNull($reflectionClassConstant->getDocComment());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC_WITH_DOC_COMMENT');

        $this->assertSame('/**
     * Doc comment
     */', $reflectionClassConstant->getDocComment());
    }

    public function testGetModifiers(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertSame(ReflectionClassConstant::IS_PUBLIC, $reflectionClassConstant->getModifiers());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PROTECTED');

        $this->assertSame(ReflectionClassConstant::IS_PROTECTED, $reflectionClassConstant->getModifiers());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PRIVATE');

        $this->assertSame(ReflectionClassConstant::IS_PRIVATE, $reflectionClassConstant->getModifiers());

        if (PHP_VERSION_ID < 80100) {
            $this->markTestSkipped('Final constants are only available in PHP 8.1+');
        }

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC_FINAL');

        $this->assertSame(ReflectionClassConstant::IS_PUBLIC & ReflectionClassConstant::IS_FINAL, $reflectionClassConstant->getModifiers());
    }

    public function testGetName(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertSame('PUBLIC', $reflectionClassConstant->getName());
    }

    public function testGetValue(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertSame(1, $reflectionClassConstant->getValue());
    }

    public function testIsEnumCase(): void
    {
        if (PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Enum constants are only available in PHP 8.1+');
        }

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertFalse($reflectionClassConstant->isEnumCase());

        $reflectionClassConstant = new ReflectionClassConstant(EnumClass::class, 'A');

        $this->assertTrue($reflectionClassConstant->isEnumCase());
    }

    public function testIsFinal(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $this->assertFalse($reflectionClassConstant->isFinal());
    }

    public function testIsPublic(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $this->assertTrue($reflectionClassConstant->isPublic());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PROTECTED');
        $this->assertFalse($reflectionClassConstant->isPublic());
    }

    public function testIsProtected(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $this->assertFalse($reflectionClassConstant->isProtected());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PROTECTED');
        $this->assertTrue($reflectionClassConstant->isProtected());
    }

    public function testIsPrivate(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $this->assertFalse($reflectionClassConstant->isPrivate());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PRIVATE');
        $this->assertTrue($reflectionClassConstant->isPrivate());
    }
}
