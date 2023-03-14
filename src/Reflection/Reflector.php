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
    /** @var array<ReflectionUseStatement[]> */
    private static array $reflectionClassUseStatements = [];

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
        return new ReflectionType(self::getTypeNames($type));
    }

    /**
     * @return string[]
     */
    public static function getTypeNames(\ReflectionType|\ReflectionNamedType|\ReflectionUnionType|null $type): array
    {
        if (null === $type) {
            return ['null'];
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
        if (! isset(self::$reflectionClassUseStatements[$className])) {
            $class = self::createReflectionClass($className);

            if (! $class->getFileName()) {
                throw new ReflectionException('Unable to retrieve filename for class ' . $className);
            }

            $source = file_get_contents($class->getFileName());
            if (!$source) {
                throw new ReflectionException('Could not open file ' . $class->getFileName());
            }
            $tokens = token_get_all($source);

            $builtNamespace = '';
            $buildingNamespace = false;
            $matchedNamespace = false;

            $useStatements = [];
            $record = false;

            $currentUse = [
                'class' => '',
                'as' => '',
            ];

            foreach ($tokens as $token) {
                if ($token[0] === T_NAMESPACE) {
                    $buildingNamespace = true;

                    if ($matchedNamespace) {
                        break;
                    }
                }

                if ($buildingNamespace) {
                    if ($token === ';') {
                        $buildingNamespace = false;
                        continue;
                    }

                    switch ($token[0]) {
                        case T_STRING:
                        case T_NS_SEPARATOR:
                            $builtNamespace .= $token[1];
                            break;
                    }

                    continue;
                }

                if (!is_array($token)) {
                    if ($record) {
                        /** @var class-string $className */
                        $className = basename($currentUse['class']);
                        $useStatements[] = new ReflectionUseStatement($className, $currentUse['as']);
                        $record = false;
                        $currentUse = [
                            'class' => '',
                            'as' => '',
                        ];
                    }

                    continue;
                }

                if ($token[0] === T_CLASS) {
                    break;
                }

                if (strcasecmp($builtNamespace, $class->getNamespaceName()) === 0) {
                    $matchedNamespace = true;
                }

                if ($token[0] === T_USE) {
                    $record = 'class';
                }

                if ($token[0] === T_AS) {
                    $record = 'as';
                }

                if ($record) {
                    switch ($token[0]) {
                        case T_STRING:
                        case T_NS_SEPARATOR:
                            $currentUse[$record] .= $token[1];
                            break;
                    }
                }

                if ($token[2] >= $class->getStartLine()) {
                    break;
                }
            }

            self::$reflectionClassUseStatements[$className] = $useStatements;
        }

        return self::$reflectionClassUseStatements[$className];
    }
}
