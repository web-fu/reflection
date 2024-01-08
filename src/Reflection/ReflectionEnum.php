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

use ReflectionEnumUnitCase;

class ReflectionEnum extends ReflectionClass
{
    private \ReflectionEnum $reflectionEnum;

    public function __construct(object|string $objectOrClass)
    {
        $this->reflectionEnum = new \ReflectionEnum($objectOrClass);
    }

    public function getBackingType(): ReflectionType|null
    {
        return Reflector::createReflectionType($this->reflectionEnum->getBackingType());
    }

    public function getCase(string $caseName): ReflectionEnumUnitCase
    {
        return $this->reflectionEnum->getCase($caseName);
    }

    /**
     * @return array<string, ReflectionEnumUnitCase>
     */
    public function getCases(): array
    {
        return $this->reflectionEnum->getCases();
    }

    public function hasCase(string $caseName): bool
    {
        return $this->reflectionEnum->hasCase($caseName);
    }

    public function isBacked(): bool
    {
        return $this->reflectionEnum->isBacked();
    }
}
