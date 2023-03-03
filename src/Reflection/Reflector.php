<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class Reflector
{
    /** @var ReflectionClass[]  */
    private static array $reflectionClasses;

    public static function createReflectionClass(object|string $objectOrClass): ReflectionClass
    {
        /** @var class-string $name */
        $name = self::getClassName($objectOrClass);

        if (! array_key_exists($name, self::$reflectionClasses)) {
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
}
