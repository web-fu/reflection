<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionParameter extends AbstractReflection
{
    private \ReflectionParameter $reflectionParameter;

    /**
     * @param string|mixed[]|object $function
     */
    public function __construct(string|array|object $function, int|string $param)
    {
        $this->reflectionParameter = new \ReflectionParameter($function, $param);
    }

    public function allowsNull(): bool
    {
        return $this->reflectionParameter->allowsNull();
    }

    public function canBePassedByValue(): bool
    {
        return $this->reflectionParameter->canBePassedByValue();
    }

    /**
     * @return \ReflectionAttribute[]
     */
    public function getAttributes(string|null $name = null, int $flags = 0): array
    {
        return $this->reflectionParameter->getAttributes($name, $flags);
    }

    public function getDeclaringClass(): ReflectionClass|null
    {
        if (!$object = $this->reflectionParameter->getDeclaringClass()) {
            return null;
        }

        return Reflector::createReflectionClass($object);
    }

    public function getDeclaringFunction(): ReflectionFunctionAbstract
    {
        $method = $this->reflectionParameter->getDeclaringFunction();
        if ($method instanceof \ReflectionMethod) {
            return Reflector::createReflectionMethod($method->getDeclaringClass(), $method->getName());
        }

        $closure = $method->getClosureThis();
        if (!$closure instanceof \Closure) {
            throw new ReflectionException('Impossible to get closure');
        }

        return Reflector::createReflectionFunction($closure);
    }

    public function getDefaultValue(): mixed
    {
        return $this->reflectionParameter->getDefaultValue();
    }

    public function getDefaultValueConstantName(): string|null
    {
        return $this->reflectionParameter->getDefaultValueConstantName();
    }

    public function getDocComment(): string|null
    {
        return $this->getDeclaringClass()?->getDocComment();
    }

    public function getName(): string
    {
        return $this->reflectionParameter->getName();
    }

    public function getPosition(): int
    {
        return $this->reflectionParameter->getPosition();
    }

    public function getType(): ReflectionType
    {
        return Reflector::createReflectionType($this->reflectionParameter->getType());
    }

    public function hasType(): bool
    {
        return $this->reflectionParameter->hasType();
    }

    public function isDefaultValueAvailable(): bool
    {
        return $this->reflectionParameter->isDefaultValueAvailable();
    }

    public function isDefaultValueConstant(): bool
    {
        return $this->reflectionParameter->isDefaultValueConstant();
    }

    public function isOptional(): bool
    {
        return $this->reflectionParameter->isOptional();
    }

    public function isPassedByReference(): bool
    {
        return $this->reflectionParameter->isPassedByReference();
    }

    public function isVariadic(): bool
    {
        return $this->reflectionParameter->isVariadic();
    }

    public function __toString(): string
    {
        return $this->reflectionParameter->__toString();
    }
}
