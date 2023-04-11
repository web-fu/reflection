<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionClass extends AbstractReflection
{
    private \ReflectionClass $reflectionClass;

    /**
     * @param object|class-string $objectOrClass
     */
    public function __construct(object|string $objectOrClass)
    {
        $this->reflectionClass = new \ReflectionClass($objectOrClass);
    }

    /**
     * @return \ReflectionAttribute[]
     */
    public function getAttributes(string|null $name = null, int $flags = 0): array
    {
        return $this->reflectionClass->getAttributes($name, $flags);
    }

    public function getConstant(string $name): mixed
    {
        foreach ($this->getReflectionConstants() as $constant) {
            if ($constant->getName() === $name) {
                return $constant->getValue();
            }
        }
        throw new ReflectionException('Undefined constant name: ' . $name);
    }

    /**
     * @return array<string, mixed>
     */
    public function getConstants(int|null $filter = null): array
    {
        $result = [];
        foreach ($this->getReflectionConstants($filter) as $constant) {
            $result[$constant->getName()] = $constant->getValue();
        }
        return $result;
    }

    public function getConstructor(): ReflectionMethod|null
    {
        if (! $this->reflectionClass->getConstructor()) {
            return null;
        }

        return new ReflectionMethod($this->getName(), '__construct');
    }

    /**
     * @return array<string, mixed>
     */
    public function getDefaultProperties(): array
    {
        return $this->reflectionClass->getDefaultProperties();
    }

    public function getDocComment(): string|null
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
     * @return array<string, self>
     */
    public function getInterfaces(): array
    {
        return array_map(fn (\ReflectionClass $class) => new self($class->getName()), $this->reflectionClass->getInterfaces());
    }

    public function getMethod(string $name): ReflectionMethod
    {
        return new ReflectionMethod($this->reflectionClass->getName(), $this->reflectionClass->getMethod($name)->getName());
    }

    /**
     * @return ReflectionMethod[]
     */
    public function getMethods(int|null $filter = null): array
    {
        return array_map(fn (\ReflectionMethod $method) =>
            $this->getMethod($method->getName()), $this->reflectionClass->getMethods($filter));
    }

    public function getModifiers(): int
    {
        return $this->reflectionClass->getModifiers();
    }

    /**
     * @return class-string
     */
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

        return new self($parentClass->getName());
    }

    /**
     * @return ReflectionProperty[]
     */
    public function getProperties(int|null $filter = null): array
    {
        return array_map(fn (\ReflectionProperty $reflectionProperty) => new ReflectionProperty($this->reflectionClass->getName(), $reflectionProperty->getName()), $this->reflectionClass->getProperties($filter));
    }

    public function getProperty(string $name): ReflectionProperty|null
    {
        if (!$this->reflectionClass->hasProperty($name)) {
            return null;
        }
        return new ReflectionProperty($this->reflectionClass->getName(), $name);
    }

    public function getReflectionConstant(string $name): ReflectionClassConstant|null
    {
        if (!$this->reflectionClass->hasConstant($name)) {
            return null;
        }
        return new ReflectionClassConstant($this->reflectionClass->getName(), $name);
    }

    /**
     * @return ReflectionClassConstant[]
     */
    public function getReflectionConstants(int|null $filter = null): array
    {
        return array_map(fn (\ReflectionClassConstant $reflectionClassConstant) => new ReflectionClassConstant($this->reflectionClass->getName(), $reflectionClassConstant->getName()), $this->reflectionClass->getReflectionConstants($filter));
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
     * @return array<string, mixed>
     */
    public function getStaticProperties(): ?array
    {
        return $this->reflectionClass->getStaticProperties();
    }

    public function getStaticPropertyValue(string $propertyName, mixed $default = null): mixed
    {
        if (!$this->hasProperty($propertyName)) {
            throw new ReflectionException('Undefined property name: ' . $propertyName);
        }

        return $this->reflectionClass->getStaticPropertyValue($propertyName, $default);
    }

    /**
     * @return string[]
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
        return array_map(fn (\ReflectionClass $reflectionClass) => new self($reflectionClass->getName()), $this->reflectionClass->getTraits());
    }

    /**
     * @return ReflectionUseStatement[]
     */
    public function getUseStatements(): array
    {
        return Reflector::createReflectionClassUseStatements($this->getName());
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

    public function implementsInterface(object|string $objectOrClass): bool
    {
        $reflection = new self($objectOrClass);

        return $this->reflectionClass->implementsInterface($reflection->getName());
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

    public function isSubclassOf(object|string $objectOrClass): bool
    {
        $reflection = new self($objectOrClass);

        return $this->reflectionClass->isSubclassOf($reflection->getName());
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
