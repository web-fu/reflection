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
use WebFu\Reflection\ReflectionTypeExtended;

/**
 * @coversNothing
 */
class ReflectionTypeExtendedTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__.'/../../Fixtures/example.php';
    }

    /**
     * @covers \WebFu\Reflection\ReflectionTypeExtended::getTypeNames
     */
    public function testGetTypeNames(): void
    {
        $reflectionType = new ReflectionTypeExtended(['string'], ['class-string']);

        $this->assertEquals(['string'], $reflectionType->getTypeNames());
    }

    /**
     * @covers \WebFu\Reflection\ReflectionTypeExtended::getDocBlockTypeNames
     */
    public function testGetDocBlockTypeNames(): void
    {
        $reflectionType = new ReflectionTypeExtended(['string'], ['class-string']);

        $this->assertEquals(['class-string'], $reflectionType->getDocBlockTypeNames());
    }
}
