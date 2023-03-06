<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class Reflector
{
    /** @var ReflectionClass[]  */
    private static array $reflectionClasses;
    /** @var array<ReflectionMethod[]> */
    private static array $reflectionMethods;

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
}
