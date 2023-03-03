<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionClass
{
    private \ReflectionClass $reflectionClass;

    /**
     * @param object|class-string $objectOrClass
     */
    public function __construct(object|string $objectOrClass)
    {
        $this->reflectionClass = new \ReflectionClass($objectOrClass);
    }

    public function getNativeReflectionClass(): \ReflectionClass
    {
        return $this->reflectionClass;
    }

    /**
     * @return \ReflectionAttribute[]
     */
    public function getAttributes(?string $name = null, int $flags = 0): array
    {
        return $this->reflectionClass->getAttributes($name, $flags);
    }

    public function getConstant(string $name): mixed
    {
        return $this->reflectionClass->getConstant($name);
    }

    /**
     * @return mixed[]
     */
    public function getConstants(?int $filter = null): array
    {
        return $this->reflectionClass->getConstants($filter);
    }

    public function getConstructor(): \ReflectionMethod|null
    {
        return $this->reflectionClass->getConstructor();
    }

    /**
     * @return mixed[]
     */
    public function getDefaultProperties(): array
    {
        return $this->reflectionClass->getDefaultProperties();
    }

    public function getDocComments(): string|null
    {
        return $this->reflectionClass->getDocComment() ?: null;
    }

    public function getEndLine(): int|null
    {
        return $this->reflectionClass->getEndLine() ?: null;
    }

    public function getExtension(): \ReflectionExtension|null
    {
        return $this->reflectionClass->getExtension();
    }

    public function getExtensionName(): string|null
    {
        return $this->reflectionClass->getExtensionName() ?: null;
    }

    public function getFileName(): string|null
    {
        return $this->reflectionClass->getFileName() ?: null;
    }

    /**
     * @return string[]
     */
    public function getInterfaceNames(): array
    {
        return $this->reflectionClass->getInterfaceNames();
    }

    /**
     * @return self[]
     */
    public function getInterfaces(): array
    {
        return array_map(function (\ReflectionClass $class) {
            return Reflector::createReflectionClass($class);
        }, $this->reflectionClass->getInterfaces());
    }

    public function getMethod(string $name): \ReflectionMethod
    {
        return $this->reflectionClass->getMethod($name);
    }

    /**
     * @return \ReflectionMethod[]
     */
    public function getMethods(?int $filter = null): array
    {
        return $this->reflectionClass->getMethods($filter);
    }

    public function getModifiers(): int
    {
        return $this->reflectionClass->getModifiers();
    }

    public function getName(): string
    {
        return $this->reflectionClass->getName();
    }

    public function getNamespaceName(): string
    {
        return $this->reflectionClass->getNamespaceName();
    }

    public function getParentClass(): self|null
    {
        $parentClass = $this->reflectionClass->getParentClass();

        if (!$parentClass) {
            return null;
        }

        return Reflector::createReflectionClass($parentClass);
    }

    /**
     * @return \ReflectionProperty[]
     */
    public function getProperties(?int $filter = null): array
    {
        return $this->reflectionClass->getProperties($filter);
    }

    public function getProperty(string $name): \ReflectionProperty
    {
        return $this->reflectionClass->getProperty($name);
    }

    public function getReflectionConstant(string $name): \ReflectionClassConstant|null
    {
        return $this->reflectionClass->getReflectionConstant($name)  ?: null;
    }

    /**
     * @return \ReflectionClassConstant[]
     */
    public function getReflectionConstants(?int $filter = null): array
    {
        return $this->reflectionClass->getReflectionConstants($filter);
    }

    public function getShortName(): string
    {
        return $this->reflectionClass->getShortName();
    }

    public function getStartLine(): int|null
    {
        return $this->reflectionClass->getStartLine() ?: null;
    }

    /**
     * @return mixed[]
     */
    public function getStaticProperties(): ?array
    {
        return $this->reflectionClass->getStaticProperties();
    }

    public function getStaticPropertyValue(string $propertyName, mixed $default = null): mixed
    {
        return $this->reflectionClass->getStaticPropertyValue($propertyName, $default);
    }

    /**
     * @return mixed[]
     */
    public function getTraitAliases(): array
    {
        return $this->reflectionClass->getTraitAliases();
    }

    /**
     * @return string[]
     */
    public function getTraitNames(): array
    {
        return $this->reflectionClass->getTraitNames();
    }

    /**
     * @return self[]
     */
    public function getTraits(): array
    {
        return array_map(function (\ReflectionClass $reflectionClass) {
            return Reflector::createReflectionClass($reflectionClass);
        }, $this->reflectionClass->getTraits());
    }

    public function hasConstant(string $name): bool
    {
        return $this->reflectionClass->hasConstant($name);
    }

    public function hasMethod(string $name): bool
    {
        return $this->reflectionClass->hasMethod($name);
    }

    public function hasProperty(string $name): bool
    {
        return $this->reflectionClass->hasProperty($name);
    }

    public function implementsInterface(\ReflectionClass|string $interface): bool
    {
        return $this->reflectionClass->implementsInterface($interface);
    }

    public function inNamespace(): bool
    {
        return $this->reflectionClass->inNamespace();
    }

    public function isAbstract(): bool
    {
        return $this->reflectionClass->isAbstract();
    }

    public function isAnonymous(): bool
    {
        return $this->reflectionClass->isAnonymous();
    }

    public function isCloneable(): bool
    {
        return $this->reflectionClass->isCloneable();
    }

    public function isEnum(): bool
    {
        return PHP_VERSION_ID >= 80100 && $this->reflectionClass->isEnum();
    }

    public function isFinal(): bool
    {
        return $this->reflectionClass->isFinal();
    }

    public function isInstance(object $object): bool
    {
        return $this->reflectionClass->isInstance($object);
    }

    public function isInstantiable(): bool
    {
        return $this->reflectionClass->isInstantiable();
    }

    public function isInterface(): bool
    {
        return $this->reflectionClass->isInterface();
    }

    public function isInternal(): bool
    {
        return $this->reflectionClass->isInternal();
    }

    public function isIterable(): bool
    {
        return $this->reflectionClass->isIterable();
    }

    public function isReadOnly(): bool
    {
        return PHP_VERSION_ID >= 80100 && $this->reflectionClass->isReadOnly();
    }

    public function isSubclassOf(self|string $objectOrClass): bool
    {
        if ($objectOrClass instanceof self) {
            return $this->reflectionClass->isSubclassOf($objectOrClass->getNativeReflectionClass());
        }

        return $this->reflectionClass->isSubclassOf($objectOrClass);
    }

    public function isTrait(): bool
    {
        return $this->reflectionClass->isTrait();
    }

    public function isUserDefined(): bool
    {
        return $this->reflectionClass->isUserDefined();
    }

    public function newInstance(mixed ...$args): object
    {
        return $this->reflectionClass->newInstance(...$args);
    }

    /**
     * @param mixed[] $args
     */
    public function newInstanceArgs(array $args = []): object
    {
        return $this->reflectionClass->newInstanceArgs($args);
    }

    public function newInstanceWithoutConstructor(): object
    {
        return $this->reflectionClass->newInstanceWithoutConstructor();
    }

    public function setStaticPropertyValue(string $name, mixed $value): void
    {
        $this->reflectionClass->setStaticPropertyValue($name, $value);
    }

    public function __toString(): string
    {
        return $this->reflectionClass->__toString();
    }
}