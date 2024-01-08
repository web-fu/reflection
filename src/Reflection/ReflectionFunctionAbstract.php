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
use ReflectionExtension;

abstract class ReflectionFunctionAbstract extends AbstractReflection
{
    protected \ReflectionFunctionAbstract $reflectionFunction;

    abstract public function __toString(): string;

    /**
     * @return ReflectionAttribute[]
     */
    public function getAttributes(string|null $name = null, int $flags = 0): array
    {
        return $this->reflectionFunction->getAttributes($name, $flags);
    }

    public function getClosureScopeClass(): ReflectionClass|null
    {
        if (!$closureScopeClass = $this->reflectionFunction->getClosureScopeClass()) {
            return null;
        }

        return new ReflectionClass($closureScopeClass->getName());
    }

    public function getClosureThis(): object|null
    {
        return $this->reflectionFunction->getClosureThis();
    }

    /**
     * @return array<string, mixed>
     */
    public function getClosureUsedVariables(): array
    {
        if (PHP_VERSION_ID < 80100) {
            throw new WrongPhpVersionException('getClosureUsedVariables() is not available for PHP versions lower than 8.1.0');
        }

        return $this->reflectionFunction->getClosureUsedVariables();
    }

    public function getDocComment(): string|null
    {
        return $this->reflectionFunction->getDocComment() ?: null;
    }

    public function getEndLine(): int|null
    {
        return $this->reflectionFunction->getEndLine() ?: null;
    }

    public function getExtension(): ReflectionExtension|null
    {
        return $this->reflectionFunction->getExtension();
    }

    public function getExtensionName(): string|null
    {
        return $this->reflectionFunction->getExtensionName() ?: null;
    }

    public function getFileName(): string|null
    {
        return $this->reflectionFunction->getFileName() ?: null;
    }

    public function getName(): string
    {
        return $this->reflectionFunction->getName();
    }

    public function getNamespaceName(): string
    {
        return $this->reflectionFunction->getNamespaceName();
    }

    public function getNumberOfParameters(): int
    {
        return $this->reflectionFunction->getNumberOfParameters();
    }

    public function getNumberOfRequiredParameters(): int
    {
        return $this->reflectionFunction->getNumberOfRequiredParameters();
    }

    /**
     * @return ReflectionParameter[]
     */
    abstract public function getParameters(): array;

    public function getReturnType(): ReflectionType
    {
        return Reflector::createReflectionType($this->reflectionFunction->getReturnType());
    }

    /**
     * @return string[]
     */
    public function getReturnTypeNames(): array
    {
        return Reflector::getTypeNames($this->reflectionFunction->getReturnType());
    }

    public function getShortName(): string
    {
        return $this->reflectionFunction->getShortName();
    }

    public function getStartLine(): int|null
    {
        return $this->reflectionFunction->getStartLine() ?: null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getStaticVariables(): array
    {
        return $this->reflectionFunction->getStaticVariables();
    }

    public function getTentativeReturnType(): ReflectionType|null
    {
        if (PHP_VERSION_ID < 80100) {
            throw new WrongPhpVersionException('getTentativeReturnType() is not available for PHP versions lower than 8.1.0');
        }

        if (!$this->reflectionFunction->hasTentativeReturnType()) {
            return null;
        }

        return Reflector::createReflectionType($this->reflectionFunction->getTentativeReturnType());
    }

    public function hasReturnType(): bool
    {
        return $this->reflectionFunction->hasReturnType();
    }

    public function hasTentativeReturnType(): bool
    {
        if (PHP_VERSION_ID < 80100) {
            throw new WrongPhpVersionException('hasTentativeReturnType() is not available for PHP versions lower than 8.1.0');
        }

        return $this->reflectionFunction->hasTentativeReturnType();
    }

    public function inNamespace(): bool
    {
        return $this->reflectionFunction->inNamespace();
    }

    public function isClosure(): bool
    {
        return $this->reflectionFunction->isClosure();
    }

    public function isDeprecated(): bool
    {
        return $this->reflectionFunction->isDeprecated();
    }

    public function isGenerator(): bool
    {
        return $this->reflectionFunction->isGenerator();
    }

    public function isInternal(): bool
    {
        return $this->reflectionFunction->isInternal();
    }

    public function isStatic(): bool
    {
        return $this->reflectionFunction->isStatic();
    }

    public function isUserDefined(): bool
    {
        return $this->reflectionFunction->isUserDefined();
    }

    public function isVariadic(): bool
    {
        return $this->reflectionFunction->isVariadic();
    }

    public function returnsReference(): bool
    {
        return $this->reflectionFunction->returnsReference();
    }
}
