<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionTypeExtended
{
    public function __construct(private array $types = [], private array $docBlockTypes = [])
    {
    }
}

