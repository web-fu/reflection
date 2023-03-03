<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionMethod extends ReflectionFunctionAbstract
{
    public const IS_STATIC = \ReflectionMethod::IS_STATIC;
    public const IS_PUBLIC = \ReflectionMethod::IS_PUBLIC;
    public const IS_PROTECTED = \ReflectionMethod::IS_PROTECTED;
    public const IS_PRIVATE = \ReflectionMethod::IS_PRIVATE;
    public const IS_ABSTRACT = \ReflectionMethod::IS_ABSTRACT;
    public const IS_FINAL = \ReflectionMethod::IS_FINAL;

    /* Methods */
    public function __construct(object|string $objectOrMethod, string $method)
    {
        $this->reflectionFunction = new \ReflectionMethod($objectOrMethod, $method);
    }

    public function getClosure(?object $object = null): \Closure|null
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->getClosure($object);
    }

    public function getDeclaringClass(): ReflectionClass
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return Reflector::createReflectionClass($this->reflectionFunction->getDeclaringClass());
    }

    public function getModifiers(): int
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->getModifiers();
    }

    public function getPrototype(): \ReflectionMethod
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->getPrototype();
    }

    public function hasPrototype(): bool
    {
        return $this->reflectionFunction->hasReturnType();
    }

    public function invoke(?object $object, mixed ...$args): mixed
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->invoke($object, ...$args);
    }

    /**
     * @param mixed[] $args
     */
    public function invokeArgs(?object $object, array $args): mixed
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->invoke($object, $args);
    }

    public function isAbstract(): bool
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isAbstract();
    }

    public function isConstructor(): bool
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isConstructor();
    }

    public function isDestructor(): bool
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isDestructor();
    }

    public function isFinal(): bool
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isFinal();
    }

    public function isPrivate(): bool
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isPrivate();
    }

    public function isProtected(): bool
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isProtected();
    }

    public function isPublic(): bool
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isPublic();
    }

    public function setAccessible(bool $accessible): void
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        $this->reflectionFunction->setAccessible($accessible);
    }

    public function __toString(): string
    {
        return $this->reflectionFunction->__toString();
    }
}