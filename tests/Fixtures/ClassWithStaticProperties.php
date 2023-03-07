<?php

declare(strict_types=1);

namespace WebFu\Tests\Fixtures;

class ClassWithStaticProperties
{
    public static int $public = 1;
    protected static int $protected = 2;
    private static int $private = 3;
}