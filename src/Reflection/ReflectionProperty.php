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
use ReflectionNamedType;
use ReflectionUnionType;

class ReflectionProperty extends AbstractReflection
{
    public const IS_STATIC    = 16;
    public const IS_READONLY  = 128;
    public const IS_PUBLIC    = 1;
    public const IS_PROTECTED = 2;
    public const IS_PRIVATE   = 4;

    private \ReflectionProperty $reflectionProperty;

    public function __construct(object|string $class, string $property)
    {
        $this->reflectionProperty = new \ReflectionProperty($class, $property);
    }

    public function __toString(): string
    {
        return $this->reflectionProperty->__toString();
    }

    /**
     * @return ReflectionAttribute[]
     */
    public function getAttributes(string|null $name = null, int $flags = 0): array
    {
        return $this->reflectionProperty->getAttributes($name, $flags);
    }

    public function getDeclaringClass(): ReflectionClass
    {
        return new ReflectionClass($this->reflectionProperty->getDeclaringClass()->getName());
    }

    public function getDefaultValue(): mixed
    {
        return $this->reflectionProperty->getDefaultValue();
    }

    public function getDocComment(): string|null
    {
        return $this->reflectionProperty->getDocComment() ?: null;
    }

    /**
     * @return string[]
     */
    public function getTypeNames(): array
    {
        $reflectionType = $this->reflectionProperty->getType();

        if (!$reflectionType) {
            return ['mixed'];
        }

        /** @var ReflectionNamedType[] $reflectionTypes */
        $reflectionTypes = $reflectionType instanceof ReflectionUnionType
            ? $reflectionType->getTypes()
            : [$reflectionType];

        return array_map(fn (ReflectionNamedType $type): string => $type->getName(), $reflectionTypes);
    }

    /**
     * @return string[]
     */
    public function getDocTypeNames(): array
    {
        $annotations = array_filter($this->getAnnotations(), fn (string $annotation) => str_starts_with($annotation, '@var'));

        if (!count($annotations)) {
            return [];
        }

        if (count($annotations) > 1) {
            throw new ReflectionException('Invalid PHPDoc annotation');
        }

        $varAnnotation = array_pop($annotations);

        $docTypes = preg_replace('/@var\s/', '$1', $varAnnotation);

        assert(is_string($docTypes));

        $docTypesList         = explode('|', $docTypes);
        $docTypesListResolved = [];

        foreach ($docTypesList as $docType) {
            $isArray = false;

            preg_match('/array<(?<group1>[a-z]+)>|(?<group2>[a-z]+)\[\]/i', $docType, $matches);

            if ($matches) {
                $docType = $matches['group1'].($matches['group2'] ?? '');
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

    public function getTypeExtended(): ReflectionTypeExtended
    {
        return new ReflectionTypeExtended($this->getTypeNames(), $this->getDocTypeNames());
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
        if (PHP_VERSION_ID < 80100) {
            throw new WrongPhpVersionException('isReadOnly() is not available for PHP versions lower than 8.1.0');
        }

        return $this->reflectionProperty->isReadOnly();
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
}
