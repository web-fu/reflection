<?php

namespace WebFu\Tests\Fixtures;

class ClassWithIntersectionTypes
{
    public \Iterator&\Countable $intersection;
}