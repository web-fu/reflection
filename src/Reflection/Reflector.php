<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class Reflector
{
    /** @var ReflectionClass[] */
    private static array $reflectionClasses = [];
    /** @var array<ReflectionMethod[]> */
    private static array $reflectionMethods = [];
    /** @var array<ReflectionProperty[]> */
    private static array $reflectionProperties = [];
    /** @var array<ReflectionClassConstant[]> */
    private static array $reflectionClassConstants = [];
    /** @var array<ReflectionUseStatement[]> */
    private static array $reflectionClassUseStatements = [];
    /** @var array<ReflectionType[]> */
    private static array $reflectionClassTypesResolved = [];

    public static function createReflectionClass(object|string $objectOrClass): ReflectionClass
    {
        /** @var class-string $name */
        $name = self::getClassName($objectOrClass);

        return self::$reflectionClasses[$name] ??= new ReflectionClass($name);
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

        return self::$reflectionMethods[$name][$method] ??= new ReflectionMethod($name, $method);
    }

    public static function createReflectionFunction(\Closure $closure): ReflectionFunction
    {
        return new ReflectionFunction($closure);
    }

    public static function createReflectionType(\ReflectionType|\ReflectionNamedType|\ReflectionUnionType|null $type): ReflectionType
    {
        return new ReflectionType(self::getTypeNames($type));
    }

    /**
     * @return string[]
     */
    public static function getTypeNames(\ReflectionType|\ReflectionNamedType|\ReflectionUnionType|null $type): array
    {
        if (null === $type) {
            return ['mixed'];
        }

        if ($type instanceof \ReflectionNamedType) {
            return [$type->getName()];
        }

        assert($type instanceof \ReflectionUnionType);

        return array_map(fn (\ReflectionNamedType $type): string => $type->getName(), $type->getTypes());
    }

    public static function createReflectionProperty(object|string $objectOrClass, string $property): ReflectionProperty
    {
        /** @var class-string $name */
        $name = self::getClassName($objectOrClass);

        return self::$reflectionProperties[$name][$property] ??= new ReflectionProperty($name, $property);
    }

    public static function createReflectionClassConstant(object|string $objectOrClass, string $constant): ReflectionClassConstant
    {
        /** @var class-string $name */
        $name = self::getClassName($objectOrClass);

        return self::$reflectionClassConstants[$name][$constant] ??= new ReflectionClassConstant($name, $constant);
    }

    /**
     * @param string|string[]|object $function
     */
    public static function createReflectionParameter(string|array|object $function, int|string $param): ReflectionParameter
    {
        return new ReflectionParameter($function, $param);
    }

    /**
     * @param class-string $className
     * @return ReflectionUseStatement[]
     */
    public static function createReflectionClassUseStatements(string $className): array
    {
        if (!isset(self::$reflectionClassUseStatements[$className])) {
            $reflectionClass = self::createReflectionClass($className);

            if (!$reflectionClass->getFileName()) {
                throw new ReflectionException('Unable to retrieve filename for class ' . $className);
            }

            $source = file_get_contents($reflectionClass->getFileName());
            if (!$source) {
                throw new ReflectionException('Could not open file ' . $reflectionClass->getFileName());
            }

            $tokens = token_get_all($source);

            $builtNamespace = '';
            $useStatements = [];
            $class = '';
            $as = '';
            $buildingNamespace = false;
            $buildingUse = false;
            $buildingAs = false;

            foreach ($tokens as $token) {
                if (is_array($token)) {
                    if ($token[0] === T_NAMESPACE) {
                        $buildingNamespace = true;
                    }

                    if ($token[0] === T_USE) {
                        $buildingUse = true;
                    }

                    if ($token[0] === T_AS) {
                        $buildingAs = true;
                    }
                }

                if ($token === ';') {
                    $buildingNamespace = false;
                    $buildingUse = false;
                    $buildingAs = false;

                    if ($builtNamespace) {
                        $builtNamespace = trim($builtNamespace);
                    }

                    if ($class) {
                        /** @var class-string $class */
                        $class = trim($class);
                        $as = $as ? trim($as) : $class;
                        $useStatements[] = new ReflectionUseStatement($class, $as);
                        $class = '';
                        $as = '';
                    }
                }

                if ($buildingNamespace) {
                    if ($token[0] === T_NAMESPACE) {
                        continue;
                    }

                    $builtNamespace .= $token[1];

                    continue;
                }

                if ($buildingUse) {
                    if ($token[0] === T_USE) {
                        continue;
                    }

                    if ($token[0] === T_AS) {
                        continue;
                    }

                    if (! $buildingAs) {
                        $class .= $token[1];
                    } else {
                        $as .= $token[1];
                    }
                }
            }

            self::$reflectionClassUseStatements[$className] = $useStatements;
        }

        return self::$reflectionClassUseStatements[$className];
    }

    /**
     * @param class-string $className
     */
    public static function typeResolver(string $className, string $typeName): ReflectionType|null
    {
        $reflectionClass = self::createReflectionClass($className);

        foreach ($reflectionClass->getUseStatements() as $useStatement) {
            if ($useStatement->getAs() === $typeName) {
                return new ReflectionType([$useStatement->getClassName()]);
            }
        }

        $maybeClass = $reflectionClass->getNamespaceName() . '\\' . $typeName;
        if (class_exists($maybeClass)) {
            return new ReflectionType([$maybeClass]);
        }

        return null;
    }
}
