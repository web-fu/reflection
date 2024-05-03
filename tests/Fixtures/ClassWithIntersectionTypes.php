<?php

namespace WebFu\Reflection\Tests\Fixtures;

class ClassWithIntersectionTypes
{
    public \Iterator&\Countable $intersection;
}