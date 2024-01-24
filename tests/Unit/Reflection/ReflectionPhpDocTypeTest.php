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
use WebFu\Reflection\ReflectionPhpDocType;
use WebFu\Tests\Fixtures\ClassWithDocComments;

/**
 * @covers \WebFu\Reflection\ReflectionPhpDocType
 */
class ReflectionPhpDocTypeTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__.'/../../Fixtures/example.php';
    }

    /**
     * @covers \WebFu\Reflection\ReflectionPhpDocType::getTypeNames
     */
    public function testGetTypeNames(): void
    {
        $reflectionType = new ReflectionPhpDocType(['string'], ['class-string']);

        $this->assertEquals(['string'], $reflectionType->getTypeNames());

        $reflectionClass    = new ReflectionClass(ClassWithDocComments::class);
        $reflectionProperty = $reflectionClass->getProperty('property');
        $reflectionType     = $reflectionProperty->getPhpDocType();

        $this->assertEquals(['string'], $reflectionType->getTypeNames());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionPhpDocType::getDocBlockTypeNames
     */
    public function testGetDocBlockTypeNames(): void
    {
        $reflectionType = new ReflectionPhpDocType(['string'], ['class-string']);

        $this->assertEquals(['class-string'], $reflectionType->getDocBlockTypeNames());

        $reflectionClass    = new ReflectionClass(ClassWithDocComments::class);
        $reflectionProperty = $reflectionClass->getProperty('property');
        $reflectionType     = $reflectionProperty->getPhpDocType();

        $this->assertEquals(['class-string'], $reflectionType->getDocBlockTypeNames());
    }
}
