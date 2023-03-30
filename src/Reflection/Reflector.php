<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class Reflector
{
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

    /**
     * @param class-string $className
     * @return ReflectionUseStatement[]
     */
    public static function createReflectionClassUseStatements(string $className): array
    {
        $reflectionClass = new ReflectionClass($className);

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

        return $useStatements;
    }

    /**
     * @param class-string $className
     */
    public static function typeResolver(string $className, string $typeName): ReflectionType|null
    {
        $reflectionClass = new ReflectionClass($className);

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
