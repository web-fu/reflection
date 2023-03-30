<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionParameter extends AbstractReflection
{
    private \ReflectionParameter $reflectionParameter;
    private ReflectionClass|null $declaringClass = null;
    private ReflectionFunctionAbstract $declaringFunction;

    /**
     * @param string|string[]|object $functionOrMethod
     */
    public function __construct(string|array|object $functionOrMethod, int|string $param)
    {
        $this->reflectionParameter = new \ReflectionParameter($functionOrMethod, $param);

        if (is_array($functionOrMethod)) {
            /** @var class-string $className */
            list($className, $methodName) = $functionOrMethod;
            $this->declaringClass = new ReflectionClass($className);
            $this->declaringFunction = new ReflectionMethod($className, $methodName);
        }

        if (is_string($functionOrMethod) && function_exists($functionOrMethod)) {
            $this->declaringFunction = new ReflectionFunction($functionOrMethod);
        }
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
        return $this->declaringClass;
    }

    public function getDeclaringFunction(): ReflectionFunctionAbstract
    {
        return $this->declaringFunction;
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

        $paramAnnotation = array_pop($docTypes);

        preg_match('/@param\s(?<param>.+)\s\$'.$this->getName().'/', $paramAnnotation, $matches);

        $docTypes = $matches['param'] ?? '';

        $docTypesList = explode('|', $docTypes);
        $docTypesListResolved = [];

        foreach ($docTypesList as $docType) {
            $isArray = false;

            preg_match('/array<(?<group1>[a-z]+)>|(?<group2>[a-z]+)\[\]/i', $docType, $matches);

            if ($matches) {
                $docType = $matches['group1'] . ($matches['group2'] ?? '');
                $isArray = true;
            }

            if (
                $this->getDeclaringClass()
                && $resolved = Reflector::typeResolver($this->getDeclaringClass()->getName(), $docType)
            ) {
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
