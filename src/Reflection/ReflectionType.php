<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionType
{
    /**
     * @param string[] $types
     */

    public function __construct(private array $types = [], private string $separator = '|')
    {
    }

    public function allowNull(): bool
    {
        return in_array('null', $this->types);
    }

    public function hasType(string $type): bool
    {
        return in_array($type, $this->types);
    }

    /**
     * @return string[]
     */
    public function getTypeNames(): array
    {
        return $this->types;
    }

    public function __toString(): string
    {
        return implode($this->separator, $this->types);
    }
}
