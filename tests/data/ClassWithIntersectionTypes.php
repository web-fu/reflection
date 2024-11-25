<?php

namespace WebFu\Reflection\Tests\data;

class ClassWithIntersectionTypes
{
    public \Iterator&\Countable $intersection;
}