<?php

declare(strict_types=1);

namespace WebFu\Reflection\Tests\data;

final class ClassFinal extends AbstractClass
{
    use GenericTrait {
        GenericTrait::publicTraitFunction as traitFunction;
    }

    public function publicFunction(): void
    {
    }
}
