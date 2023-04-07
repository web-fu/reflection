<?php

declare(strict_types=1);

namespace WebFu\Tests\Fixtures;

class ChildClass extends ParentClass
{
    use GenericTrait {
        GenericTrait::publicTraitFunction as traitFunction;
    }
}
