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

    public function getClosure(object|null $object = null): \Closure|null
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->getClosure($object);
    }

    public function getDeclaringClass(): ReflectionClass
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return new ReflectionClass($this->reflectionFunction->getDeclaringClass()->getName());
    }

    public function getModifiers(): int
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->getModifiers();
    }

    /**
     * @return ReflectionParameter[]
     */
    public function getParameters(): array
    {
        $closure = [$this->getDeclaringClass()->getName(), $this->reflectionFunction->getName()];
        return array_map(fn (\ReflectionParameter $reflectionParameter) => new ReflectionParameter($closure, $reflectionParameter->getName()), $this->reflectionFunction->getParameters());
    }

    public function getPrototype(): ReflectionMethod
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        $method = $this->reflectionFunction->getPrototype();

        return new ReflectionMethod($method->getDeclaringClass()->getName(), $method->getName());
    }

    public function getReturnTypeExtended(): ReflectionTypeExtended
    {
        return new ReflectionTypeExtended($this->getReturnTypeNames(), $this->getReturnDocTypeNames());
    }

    /**
     * @return string[]
     */
    public function getReturnDocTypeNames(): array
    {
        $docTypes = array_filter($this->getAnnotations(), fn (string $annotation) => str_starts_with($annotation, '@return'));

        if (!count($docTypes)) {
            return [];
        }

        if (count($docTypes) > 1) {
            throw new ReflectionException('Invalid PHPDoc annotation');
        }

        $returnAnnotation = array_pop($docTypes);

        preg_match('/@return\s(?<return>.+)/', $returnAnnotation, $matches);

        $docTypes = $matches['return'] ?? '';

        $docTypesList = explode('|', $docTypes);
        $docTypesListResolved = [];

        foreach ($docTypesList as $docType) {
            $isArray = false;

            preg_match('/array<(?<group1>[a-z]+)>|(?<group2>[a-z]+)\[\]/i', $docType, $matches);

            if ($matches) {
                $docType = $matches['group1'] . ($matches['group2'] ?? '');
                $isArray = true;
            }

            if ($resolved = Reflector::typeResolver($this->getDeclaringClass()->getName(), $docType)) {
                $docType = $resolved->getTypeNames()[0];
            }

            if ($isArray) {
                $docType .= '[]';
            }

            $docTypesListResolved[] = $docType;
        }

        return $docTypesListResolved;
    }

    public function hasPrototype(): bool
    {
        if (PHP_VERSION_ID < 80200) {
            return false;
        }

        return $this->reflectionFunction->hasPropotype();
    }

    public function invoke(object|null $object, mixed ...$args): mixed
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->invoke($object, ...$args);
    }

    /**
     * @param mixed[] $args
     */
    public function invokeArgs(object|null $object, array $args): mixed
    {
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->invokeArgs($object, $args);
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
