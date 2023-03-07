<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionClassConstant extends AbstractReflection
{
    /* Constants */
    public const IS_PUBLIC = \ReflectionClassConstant::IS_PUBLIC;
    public const IS_PROTECTED = \ReflectionClassConstant::IS_PROTECTED;
    public const IS_PRIVATE = \ReflectionClassConstant::IS_PRIVATE;
    public const IS_FINAL = 5;

    private \ReflectionClassConstant $reflectionClassConstant;
    /* Methods */
    public function __construct(object|string $class, string $constant)
    {
        $this->reflectionClassConstant = new \ReflectionClassConstant($class, $constant);
    }

    /**
     * @return \ReflectionAttribute[]
     */
    public function getAttributes(string|null $name = null, int $flags = 0): array
    {
        return $this->reflectionClassConstant->getAttributes($name, $flags);
    }

    public function getDeclaringClass(): ReflectionClass
    {
        return Reflector::createReflectionClass($this->reflectionClassConstant->getDeclaringClass());
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
        return PHP_VERSION_ID >= 80100 && $this->reflectionClassConstant->isEnumCase();
    }

    public function isFinal(): bool
    {
        return PHP_VERSION_ID >= 80100 && $this->reflectionClassConstant->isFinal();
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

    public function __toString(): string
    {
        return $this->reflectionClassConstant->__toString();
    }
}
