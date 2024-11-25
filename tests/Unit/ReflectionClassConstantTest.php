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

namespace WebFu\Reflection\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\ReflectionClassConstant;
use WebFu\Reflection\Tests\data\Attribute;
use WebFu\Reflection\Tests\data\ClassWithConstants;
use WebFu\Reflection\Tests\data\ClassWithFinals;
use WebFu\Reflection\Tests\data\ClassWithTypedConstants;
use WebFu\Reflection\Tests\data\EnumClass;
use WebFu\Reflection\WrongPhpVersionException;

/**
 * @group unit
 *
 * @coversDefaultClass  \WebFu\Reflection\ReflectionClassConstant
 */
class ReflectionClassConstantTest extends TestCase
{
    /**
     * @covers ::getAttributes
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
     * @covers ::getDeclaringClass
     */
    public function testGetDeclaringClass(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertEquals(new ReflectionClass(ClassWithConstants::class), $reflectionClassConstant->getDeclaringClass());
    }

    /**
     * @covers ::getDocComment
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
     * @covers ::getModifiers
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
     * @covers ::getName
     */
    public function testGetName(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertSame('PUBLIC', $reflectionClassConstant->getName());
    }

    /**
     * @covers ::getValue
     */
    public function testGetValue(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertSame(1, $reflectionClassConstant->getValue());
    }

    /**
     * @covers ::isEnumCase
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
     * @covers ::isFinal
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
     * @covers ::isPrivate
     */
    public function testIsPublic(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $this->assertTrue($reflectionClassConstant->isPublic());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PROTECTED');
        $this->assertFalse($reflectionClassConstant->isPublic());
    }

    /**
     * @covers ::isProtected
     */
    public function testIsProtected(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $this->assertFalse($reflectionClassConstant->isProtected());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PROTECTED');
        $this->assertTrue($reflectionClassConstant->isProtected());
    }

    /**
     * @covers ::isPrivate
     */
    public function testIsPrivate(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $this->assertFalse($reflectionClassConstant->isPrivate());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PRIVATE');
        $this->assertTrue($reflectionClassConstant->isPrivate());
    }

    /**
     * @covers ::__debugInfo
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
     * @covers ::__toString
     */
    public function testToString(): void
    {
        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');

        $this->assertSame('Constant [ public int PUBLIC ] { 1 }'.PHP_EOL, $reflectionClassConstant->__toString());
    }

    /**
     * @covers ::getType
     */
    public function testGetType(): void
    {
        if (PHP_VERSION_ID < 80300) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('getType() is not available for PHP versions lower than 8.3.0');

            $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
            $reflectionClassConstant->getType();

            $this->markTestSkipped('getType() is not available for PHP versions lower than 8.3.0');
        }

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $this->assertSame(['mixed'], $reflectionClassConstant->getType()->getTypeNames());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithTypedConstants::class, 'INTEGER');
        $this->assertSame(['int'], $reflectionClassConstant->getType()->getTypeNames());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithTypedConstants::class, 'INTEGER_OR_STRING');
        $this->assertSame(['int', 'string'], $reflectionClassConstant->getType()->getTypeNames());
    }

    public function testHasType(): void
    {
        if (PHP_VERSION_ID < 80300) {
            $this->expectException(WrongPhpVersionException::class);
            $this->expectExceptionMessage('hasType() is not available for PHP versions lower than 8.3.0');

            $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
            $reflectionClassConstant->hasType();

            $this->markTestSkipped('hasType() is not available for PHP versions lower than 8.3.0');
        }

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithConstants::class, 'PUBLIC');
        $this->assertFalse($reflectionClassConstant->hasType());

        $reflectionClassConstant = new ReflectionClassConstant(ClassWithTypedConstants::class, 'INTEGER');
        $this->assertTrue($reflectionClassConstant->hasType());
    }
}
