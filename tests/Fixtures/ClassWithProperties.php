<?php

declare(strict_types=1);

namespace WebFu\Tests\Fixtures;

class ClassWithProperties
{
    public int $public = 1;
    protected int $protected = 2;
    private int $private = 3;

    public int $propertyWithoutDefault;

    public static int $staticPublic = 1;
    protected static int $staticProtected = 2;
    private static int $staticPrivate = 3;
}
