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
    public const IS_PUBLIC    = 1;
    public const IS_PROTECTED = 2;
    public const IS_PRIVATE   = 4;
    public const IS_FINAL     = 5;

    private \ReflectionClassConstant $reflectionClassConstant;

    public function __construct(object|string $class, string $constant)
    {
        $this->reflectionClassConstant = new \ReflectionClassConstant($class, $constant);
    }

    public function __toString(): string
    {
        return $this->reflectionClassConstant->__toString();
    }

    /**
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'name'        => $this->getName(),
            'class'       => $this->getDeclaringClass()->getName(),
            'value'       => $this->getValue(),
            'attributes'  => $this->getAttributes(),
            'annotations' => $this->getAnnotations(),
        ];
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

    public function getType(): ReflectionType
    {
        if (PHP_VERSION_ID < 80300) {
            throw new WrongPhpVersionException('getType() is not available for PHP versions lower than 8.3.0');
        }

        $type = $this->reflectionClassConstant->getType();

        $reflectionTypeNames = reflection_type_names($type);

        return new ReflectionType($reflectionTypeNames);
    }

    public function hasType(): bool
    {
        if (PHP_VERSION_ID < 80300) {
            throw new WrongPhpVersionException('hasType() is not available for PHP versions lower than 8.3.0');
        }

        return $this->reflectionClassConstant->hasType();
    }
}
