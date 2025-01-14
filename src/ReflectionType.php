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
     * @param string[] $phpDocTypeNames
     */
    public function __construct(private array $types = [], private array $phpDocTypeNames = [])
    {
        if (empty($types)) {
            $this->types = ['mixed'];
        }
    }

    public function __toString(): string
    {
        return implode('|', $this->types);
    }

    public function __debugInfo(): array
    {
        return [
            'types'       => $this->types,
            'phpDocTypes' => $this->phpDocTypeNames,
        ];
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

    /**
     * @return string[]
     */
    public function getPhpDocTypeNames(): array
    {
        return $this->phpDocTypeNames;
    }

    public function isUnionType(): bool
    {
        return count($this->types) > 1;
    }

    public function isIntersectionType(): bool
    {
        if (PHP_VERSION_ID < 80100) {
            throw new WrongPhpVersionException('isIntersectionType() is not available for PHP versions lower than 8.1.0');
        }

        return str_contains($this->types[0], '&');
    }
}
