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

namespace WebFu\Reflection\Tests\Benchmark;

use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\Tests\Fixtures\ClassWithDocComments;

class RefectionClassBench
{
    /**
     * @Revs(10000)
     */
    public function benchConstructor(): void
    {
        $reflectionClass = new ReflectionClass(ClassWithDocComments::class);
        $reflectionClass->getUseStatements();
    }
}
