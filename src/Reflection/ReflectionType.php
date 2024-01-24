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
     * @var string[]
     */
    private array $types = [];

    /**
     * @param string[] $types
     */
    public function __construct(array $types = [], private string $separator = '|')
    {
        sort($types);
        $this->types = $types;
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
        return (empty($this->types)) ? ['mixed'] : $this->types;
    }

    public function isUnionType(): bool
    {
        return '|' === $this->separator;
    }

    public function isIntersectionType(): bool
    {
        if (PHP_VERSION_ID < 80100) {
            throw new WrongPhpVersionException('isIntersectionType() is not available for PHP versions lower than 8.1.0');
        }

        return '&' === $this->separator;
    }
}
