<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class Reflector
{
    /** @var ReflectionClass[]  */
    private static array $reflectionClasses = [];
    /** @var array<ReflectionMethod[]> */
    private static array $reflectionMethods = [];
    /** @var array<ReflectionProperty[]> */
    private static array $reflectionProperties = [];
    /** @var array<ReflectionClassConstant[]> */
    private static array $reflectionClassConstants = [];

    public static function createReflectionClass(object|string $objectOrClass): ReflectionClass
    {
        /** @var class-string $name */
        $name = self::getClassName($objectOrClass);

        if (! isset(self::$reflectionClasses[$name])) {
            self::$reflectionClasses[$name] = new ReflectionClass($name);
        }

        return self::$reflectionClasses[$name];
    }

    public static function getClassName(object|string $objectOrClass): string
    {
        if (
            $objectOrClass instanceof ReflectionClass
            || $objectOrClass instanceof \ReflectionClass
        ) {
            return $objectOrClass->getName();
        }

        if (is_object($objectOrClass)) {
            return $objectOrClass::class;
        }

        return $objectOrClass;
    }

    public static function createReflectionMethod(object|string $objectOrMethod, string $method): ReflectionMethod
    {
        /** @var class-string $name */
        $name = self::getClassName($objectOrMethod);

        if (! isset(self::$reflectionMethods[$name][$method])) {
            self::$reflectionMethods[$name][$method] = new ReflectionMethod($name, $method);
        }

        return self::$reflectionMethods[$name][$method];
    }

    public static function createReflectionFunction(\Closure $closure): ReflectionFunction
    {
        return new ReflectionFunction($closure);
    }

    public static function createReflectionType(\ReflectionType|\ReflectionNamedType|\ReflectionUnionType|null $type): ReflectionType
    {
        if (null === $type) {
            return new ReflectionType(['null']);
        }

        if ($type instanceof \ReflectionNamedType) {
            return new ReflectionType([$type->getName()]);
        }

        assert($type instanceof \ReflectionUnionType);

        return new ReflectionType(array_map(fn (\ReflectionNamedType $type): string => $type->getName(), $type->getTypes()));
    }

    public static function createReflectionProperty(object|string $objectOrClass, string $property): ReflectionProperty
    {
        /** @var class-string $name */
        $name = self::getClassName($objectOrClass);

        if (! isset(self::$reflectionProperties[$name][$property])) {
            self::$reflectionProperties[$name][$property] = new ReflectionProperty($name, $property);
        }

        return self::$reflectionProperties[$name][$property];
    }

    public static function createReflectionClassConstant(object|string $objectOrClass, string $constant): ReflectionClassConstant
    {
        /** @var class-string $name */
        $name = self::getClassName($objectOrClass);

        if (! isset(self::$reflectionClassConstants[$name][$constant])) {
            self::$reflectionClassConstants[$name][$constant] = new ReflectionClassConstant($name, $constant);
        }

        return self::$reflectionClassConstants[$name][$constant];
    }
}
