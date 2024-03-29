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

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\ReflectionClassConstant;
use WebFu\Reflection\WrongPhpVersionException;
use WebFu\Tests\Fixtures\Attribute;
use WebFu\Tests\Fixtures\ClassWithConstants;
use WebFu\Tests\Fixtures\ClassWithFinals;
use WebFu\Tests\Fixtures\EnumClass;

/**
 * @covers \WebFu\Reflection\ReflectionClassConstant
 */
class ReflectionClassConstantTest extends TestCase
{
    /**
     * @covers \WebFu\Reflection\ReflectionClassConstant::getAttributes
     */
    public function testGetAttributes(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC_WITH_ATTRIBUTE');
        $attributes              = $reflectionClassConstant->getAttributes();

        $this->assertCount(1, $attributes);
        $this->assertSame(Attribute::class, $attributes[0]->getName());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $attributes              = $reflectionClassConstant->getAttributes();

        $this->assertCount(0, $attributes);
    }

    /**
     * @covers \WebFu\Reflection\ReflectionClassConstant::getDeclaringClass
     */
    public function testGetDeclaringClass(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertEquals(new ReflectionClass(ClassWithConstants::class), $reflectionClassConstant->getDeclaringClass());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionClassConstant::getDocComment
     */
    public function testGetDocComment(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertNull($reflectionClassConstant->getDocComment());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC_WITH_DOC_COMMENT');

        $this->assertSame('/**
     * Doc comment
     */', $reflectionClassConstant->getDocComment());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionClassConstant::getModifiers
     */
    public function testGetModifiers(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertSame(ReflectionClassConstant::IS_PUBLIC, $reflectionClassConstant->getModifiers());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PROTECTED');

        $this->assertSame(ReflectionClassConstant::IS_PROTECTED, $reflectionClassConstant->getModifiers());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PRIVATE');

        $this->assertSame(ReflectionClassConstant::IS_PRIVATE, $reflectionClassConstant->getModifiers());

        if (PHP_VERSION_ID < 80100) {
            $this->markTestSkipped('Final constants are not available for PHP versions lower than 8.1.0');
        }

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithFinals::class, 'PUBLIC_FINAL');

        $this->assertSame(33, $reflectionClassConstant->getModifiers());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionClassConstant::getName
     */
    public function testGetName(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertSame('PUBLIC', $reflectionClassConstant->getName());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionClassConstant::getValue
     */
    public function testGetValue(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertSame(1, $reflectionClassConstant->getValue());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionClassConstant::isEnumCase
     */
    public function testIsEnumCase(): void
    {
        if (PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Enums are not available for PHP versions lower than 8.1.0');
        }

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertFalse($reflectionClassConstant->isEnumCase());

        $reflectionClassConstant = new ReflectionClassConstant(EnumClass::class, 'A');

        $this->assertTrue($reflectionClassConstant->isEnumCase());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionClassConstant::isFinal
     */
    public function testIsFinal(): void
    {
        if (PHP_VERSION_ID < 80100) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('isFinal() is not available for PHP versions lower than 8.1.0');

            $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
            $reflectionClassConstant->isFinal();

            self::markTestSkipped('Final keyword is not available for PHP versions lower than 8.1.0');
        }

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $this->assertFalse($reflectionClassConstant->isFinal());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithFinals::class, 'PUBLIC_FINAL');
        $this->assertTrue($reflectionClassConstant->isFinal());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionClassConstant::isPrivate
     */
    public function testIsPublic(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $this->assertTrue($reflectionClassConstant->isPublic());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PROTECTED');
        $this->assertFalse($reflectionClassConstant->isPublic());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionClassConstant::isProtected
     */
    public function testIsProtected(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $this->assertFalse($reflectionClassConstant->isProtected());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PROTECTED');
        $this->assertTrue($reflectionClassConstant->isProtected());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionClassConstant::isPrivate
     */
    public function testIsPrivate(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $this->assertFalse($reflectionClassConstant->isPrivate());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PRIVATE');
        $this->assertTrue($reflectionClassConstant->isPrivate());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionClassConstant::__debugInfo
     */
    public function testDebugInfo(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertSame([
            'name'        => 'PUBLIC',
            'class'       => ClassWithConstants::class,
            'value'       => 1,
            'attributes'  => [],
            'annotations' => [],
        ], $reflectionClassConstant->__debugInfo());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionClassConstant::__toString
     */
    public function testToString(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertSame('Constant [ public int PUBLIC ] { 1 }'.PHP_EOL, $reflectionClassConstant->__toString());
    }
}
