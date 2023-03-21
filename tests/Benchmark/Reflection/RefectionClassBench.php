<?php

declare(strict_types=1);

namespace WebFu\Tests\Benchmark\Reflection;

use WebFu\Reflection\ReflectionClass;
use WebFu\Reflection\Reflector;
use WebFu\Tests\Fixtures\ClassWithDocComments;

class RefectionClassBench
{
    /**
     * @Revs(1000)
     */
    public function benchReflector(): void
    {
        Reflector::createReflectionClass(ClassWithDocComments::class);
    }

    /**
     * @Revs(1000)
     */
    public function benchConstructor(): void
    {
        new ReflectionClass(ClassWithDocComments::class);
    }
}