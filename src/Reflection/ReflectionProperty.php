<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionProperty extends AbstractReflection
{
    public const IS_STATIC = \ReflectionProperty::IS_STATIC;
    public const IS_READONLY = 128;
    public const IS_PUBLIC = \ReflectionProperty::IS_PUBLIC;
    public const IS_PROTECTED = \ReflectionProperty::IS_PROTECTED;
    public const IS_PRIVATE = \ReflectionProperty::IS_PRIVATE;

    private \ReflectionProperty $reflectionProperty;

    public function __construct(object|string $class, string $property)
    {
        $this->reflectionProperty = new \ReflectionProperty($class, $property);
    }

    /**
     * @return \ReflectionAttribute[]
     */
    public function getAttributes(string|null $name = null, int $flags = 0): array
    {
        return $this->reflectionProperty->getAttributes($name, $flags);
    }

    public function getDeclaringClass(): ReflectionClass
    {
        return Reflector::createReflectionClass($this->reflectionProperty->getDeclaringClass());
    }

    public function getDefaultValue(): mixed
    {
        return $this->reflectionProperty->getDefaultValue();
    }

    public function getDocComment(): string|null
    {
        return $this->reflectionProperty->getDocComment() ?: null;
    }

    public function getDocTypeName(): string
    {
        $annotations = array_filter($this->getAnnotations(), fn(string $annotation) => str_starts_with($annotation, '@var'));
        $docTypes = preg_replace('/@var\s/', '$1', $annotations);

        if (!count($docTypes)) {
            return 'mixed';
        }

        if (count($docTypes) > 1) {
            throw new ReflectionException('Invalid PHPDoc annotation');
        }

        return array_pop($docTypes);
    }

    public function getModifiers(): int
    {
        return $this->reflectionProperty->getModifiers();
    }

    public function getName(): string
    {
        return $this->reflectionProperty->getName();
    }

    public function getType(): ReflectionType
    {
        return Reflector::createReflectionType($this->reflectionProperty->getType());
    }

    public function getValue(object|null $object = null): mixed
    {
        return $this->reflectionProperty->getValue($object);
    }

    public function hasDefaultValue(): bool
    {
        return $this->reflectionProperty->hasDefaultValue();
    }

    public function hasType(): bool
    {
        return $this->reflectionProperty->hasType();
    }

    public function isDefault(): bool
    {
        return $this->reflectionProperty->isDefault();
    }

    public function isInitialized(object|null $object = null): bool
    {
        return $this->reflectionProperty->isInitialized($object);
    }

    public function isPrivate(): bool
    {
        return $this->reflectionProperty->isPrivate();
    }

    public function isPromoted(): bool
    {
        return $this->reflectionProperty->isPromoted();
    }

    public function isProtected(): bool
    {
        return $this->reflectionProperty->isProtected();
    }

    public function isPublic(): bool
    {
        return $this->reflectionProperty->isPublic();
    }

    public function isReadOnly(): bool
    {
        return PHP_VERSION_ID >= 80100 && $this->reflectionProperty->isReadOnly();
    }

    public function isStatic(): bool
    {
        return $this->reflectionProperty->isStatic();
    }

    public function setAccessible(bool $accessible): void
    {
        $this->reflectionProperty->setAccessible($accessible);
    }

    public function setValue(object $object, mixed $value): void
    {
        $this->reflectionProperty->setValue($object, $value);
    }

    public function __toString(): string
    {
        return $this->reflectionProperty->__toString();
    }
}
