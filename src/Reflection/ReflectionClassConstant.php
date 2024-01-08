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

use ReflectionAttribute;

class ReflectionClassConstant extends AbstractReflection
{
    /* Constants */
    public const IS_PUBLIC    = \ReflectionClassConstant::IS_PUBLIC;
    public const IS_PROTECTED = \ReflectionClassConstant::IS_PROTECTED;
    public const IS_PRIVATE   = \ReflectionClassConstant::IS_PRIVATE;
    public const IS_FINAL     = 5;

    private \ReflectionClassConstant $reflectionClassConstant;

    /* Methods */
    public function __construct(object|string $class, string $constant)
    {
        $this->reflectionClassConstant = new \ReflectionClassConstant($class, $constant);
    }

    public function __toString(): string
    {
        return $this->reflectionClassConstant->__toString();
    }

    /**
     * @return ReflectionAttribute[]
     */
    public function getAttributes(string|null $name = null, int $flags = 0): array
    {
        return $this->reflectionClassConstant->getAttributes($name, $flags);
    }

    public function getDeclaringClass(): ReflectionClass
    {
        return new ReflectionClass($this->reflectionClassConstant->getDeclaringClass()->getName());
    }

    public function getDocComment(): string|null
    {
        return $this->reflectionClassConstant->getDocComment() ?: null;
    }

    public function getModifiers(): int
    {
        return $this->reflectionClassConstant->getModifiers();
    }

    public function getName(): string
    {
        return $this->reflectionClassConstant->getName();
    }

    public function getValue(): mixed
    {
        return $this->reflectionClassConstant->getValue();
    }

    public function isEnumCase(): bool
    {
        if (PHP_VERSION_ID < 80100) {
            throw new WrongPhpVersionException('isEnumCase() is not available for PHP versions lower than 8.1.0');
        }

        return $this->reflectionClassConstant->isEnumCase();
    }

    public function isFinal(): bool
    {
        if (PHP_VERSION_ID < 80100) {
            throw new WrongPhpVersionException('isFinal() is not available for PHP versions lower than 8.1.0');
        }

        return $this->reflectionClassConstant->isFinal();
    }

    public function isPrivate(): bool
    {
        return $this->reflectionClassConstant->isPrivate();
    }

    public function isProtected(): bool
    {
        return $this->reflectionClassConstant->isProtected();
    }

    public function isPublic(): bool
    {
        return $this->reflectionClassConstant->isPublic();
    }
}
