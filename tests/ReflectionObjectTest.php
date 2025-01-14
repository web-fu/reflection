<?php

namespace WebFu\Reflection\Tests;

use PHPUnit\Framework\TestCase;
use WebFu\Reflection\ReflectionObject;
use WebFu\Reflection\Tests\data\GenericClass;

class ReflectionObjectTest extends TestCase
{
    /**
     * @dataProvider objectProvider
     */
    public function testReflectionObject(object $object): void
    {
        $reflectionObject = new ReflectionObject($object);

        $this->assertInstanceOf(ReflectionObject::class, $reflectionObject);
    }

    public function objectProvider(): iterable
    {
        yield 'anonymous-class' => [
            'object' => new class {
                public function foo(): string
                {
                    return 'foo';
                }
            },
        ];

        yield 'generic' => [
            'object' => new GenericClass(),
        ];
    }
}