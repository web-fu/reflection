<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionParameter extends AbstractReflection
{
    private \ReflectionParameter $reflectionParameter;

    /**
     * @param string|string[]|object $function
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
        return $this->getDeclaringFunction()->getDocComment();
    }

    public function getAnnotations(): array
    {
        $functionAnnotations = parent::getAnnotations();
        return array_filter($functionAnnotations, fn (string $annotation) => str_contains($annotation, $this->getName()));
    }

    /**
     * @return string[]
     */
    public function getTypeNames(): array
    {
        return Reflector::getTypeNames($this->reflectionParameter->getType());
    }

    /**
     * @return string[]
     */
    public function getDocTypeNames(): array
    {
        $docTypes = array_filter($this->getAnnotations(), fn (string $annotation) => str_starts_with($annotation, '@param'));

        if (!count($docTypes)) {
            return [];
        }

        if (count($docTypes) > 1) {
            throw new ReflectionException('Invalid PHPDoc annotation');
        }

        $docType = array_pop($docTypes);

        preg_match('/@param\s(?<param>.+)\s\$'.$this->getName().'/', $docType, $matches);

        $name = $matches['param'] ?? '';

        return explode('|', $name);
    }

    public function getTypeExtended(): ReflectionTypeExtended
    {
        return new ReflectionTypeExtended($this->getTypeNames(), $this->getDocTypeNames());
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
