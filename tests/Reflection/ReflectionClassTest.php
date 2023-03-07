<?php

declare(strict_types=1);

namespace WebFu\Tests\Reflection;

use WebFu\Reflection\ReflectionClass;
use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionException;
use WebFu\Tests\Fixtures\ClassWithAttributes;
use WebFu\Tests\Fixtures\ClassWithConstants;
use WebFu\Tests\Fixtures\ClassWithStaticProperties;

class ReflectionClassTest extends TestCase
{
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
    public function testGetStaticProperties(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithStaticProperties::class);
        $this->assertEquals([
            'public' => 1,
            'protected' => 2,
            'private' => 3,
        ], $reflectionClass->getStaticProperties());
    }
}
