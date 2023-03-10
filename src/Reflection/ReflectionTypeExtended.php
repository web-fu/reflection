<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionTypeExtended
{
    /**
     * @param string[] $typeNames
     * @param string[] $docBlockTypeNames
     */
    public function __construct(private array $typeNames = [], private array $docBlockTypeNames = [])
    {
    }

    /**
     * @return string[]
     */
    public function getTypeNames(): array
    {
        return $this->typeNames;
    }

    /**
     * @return string[]
     */
    public function getDocBlockTypeNames(): array
    {
        return $this->docBlockTypeNames;
    }
}
