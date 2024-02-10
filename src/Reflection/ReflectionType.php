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

use InvalidArgumentException;

class ReflectionType
{
    /**
     * @var string[]
     */
    private array $types;
    /**
     * @var string[]
     */
    private array $phpDocTypeNames;

    /**
     * @param string[] $types
     * @param string[] $phpDocTypeNames
     */
    public function __construct(array $types = [], array $phpDocTypeNames = [], private string $separator = '|')
    {
        if (empty($types)) {
            $types = ['mixed'];
        }

        if (count($types) > 1 && '|' !== $separator) {
            throw new InvalidArgumentException('Union types must use the "|" separator');
        }

        if (1 === count($types) && str_contains($types[0], '&')) {
            $this->separator = '&';
        }

        $this->types           = $types;
        $this->phpDocTypeNames = $phpDocTypeNames;
    }

    public function __toString(): string
    {
        return implode($this->separator, $this->types);
    }

    public function __debugInfo(): array
    {
        return [
            'types'     => $this->types,
            'separator' => $this->separator,
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
        return '|' === $this->separator && count($this->types) > 1;
    }

    public function isIntersectionType(): bool
    {
        if (PHP_VERSION_ID < 80100) {
            throw new WrongPhpVersionException('isIntersectionType() is not available for PHP versions lower than 8.1.0');
        }

        return '&' === $this->separator;
    }
}
