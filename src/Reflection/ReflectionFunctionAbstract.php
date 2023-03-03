<?php

declare(strict_types=1);

namespace WebFu\Reflection;

abstract class ReflectionFunctionAbstract
{
    protected \ReflectionFunctionAbstract $reflectionFunction;

    /**
     * @return \ReflectionAttribute[]
     */
    public function getAttributes(?string $name = null, int $flags = 0): array
    {
        return $this->reflectionFunction->getAttributes($name, $flags);
    }

    public function getClosureScopeClass(): \ReflectionClass|null
    {
        return $this->reflectionFunction->getClosureScopeClass();
    }

    public function getClosureThis(): ?object
    {
        return $this->reflectionFunction->getClosureThis();
    }

    /**
     * @return array<string, mixed>
     */
    public function getClosureUsedVariables(): array
    {
        return PHP_VERSION_ID >= 80100 ? $this->reflectionFunction->getClosureUsedVariables() : [];
    }

    public function getDocComment(): string|null
    {
        return $this->reflectionFunction->getDocComment() ?: null;
    }

    public function getEndLine(): int|null
    {
        return $this->reflectionFunction->getEndLine() ?: null;
    }

    public function getExtension(): ?\ReflectionExtension
    {
        return $this->reflectionFunction->getExtension();
    }

    public function getExtensionName(): string|false
    {
        return $this->reflectionFunction->getExtensionName();
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
        return $this->reflectionFunction->getNumberOfRequiredParameters();
    }

    public function getNumberOfRequiredParameters(): int
    {
        return $this->reflectionFunction->getNumberOfRequiredParameters();
    }

    /**
     * @return \ReflectionParameter[]
     */
    public function getParameters(): array
    {
        return $this->reflectionFunction->getParameters();
    }

    public function getReturnType(): ?\ReflectionType
    {
        return $this->reflectionFunction->getReturnType();
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

    public function getTentativeReturnType(): ?\ReflectionType
    {
        return PHP_VERSION_ID >= 80100 ? $this->reflectionFunction->getTentativeReturnType() : null;
    }

    public function hasReturnType(): bool
    {
        return $this->reflectionFunction->hasReturnType();
    }

    public function hasTentativeReturnType(): bool
    {
        return PHP_VERSION_ID >= 80100 && $this->reflectionFunction->hasTentativeReturnType();
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

    abstract public function __toString(): string;
}
