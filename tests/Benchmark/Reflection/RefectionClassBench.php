<?php

declare(strict_types=1);

namespace WebFu\Tests\Benchmark\Reflection;

use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\Reflector;
use WebFu\Tests\Fixtures\ClassWithDocComments;

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