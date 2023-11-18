<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionEnum extends ReflectionClass
{
    private \ReflectionEnum $reflectionEnum;
    public function __construct(object|string $objectOrClass)
    {
        $this->reflectionEnum = new \ReflectionEnum($objectOrClass);
    }

    public function getBackingType(): ReflectionType|null
    {
        return Reflector::createReflectionType($this->reflectionEnum->getBackingType());
    }

    public function getCase(string $caseName): \ReflectionEnumUnitCase
    {
        return $this->reflectionEnum->getCase($caseName);
    }
}
