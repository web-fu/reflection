<?php

declare(strict_types=1);

/**
 * This file is part of web-fu/reflection
 *
 * @copyright Web-Fu <info@web-fu.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebFu\Reflection;

class ReflectionType
{
    /**
     * @param string[] $types
     */
    public function __construct(private array $types = [], private string $separator = '|')
    {
    }

    public function __toString(): string
    {
        return implode($this->separator, $this->types);
    }

    public function allowNull(): bool
    {
        return in_array('null', $this->types, true);
    }

    public function hasType(string $type): bool
    {
        return in_array($type, $this->types, true);
    }

    /**
     * @return string[]
     */
    public function getTypeNames(): array
    {
        return $this->types;
    }
}
