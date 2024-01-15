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

use ReflectionNamedType;
use ReflectionUnionType;

class Reflector
{
    public static function createReflectionType(\ReflectionType|ReflectionNamedType|ReflectionUnionType|null $reflectionType): ReflectionType
    {
        if (!$reflectionType) {
            return new ReflectionType(['mixed']);
        }

        /** @var \ReflectionNamedType[] $reflectionTypes */
        $reflectionTypes = $reflectionType instanceof \ReflectionUnionType
            ? $reflectionType->getTypes()
            : [$reflectionType];

        $typeNames = array_map(fn (\ReflectionNamedType $type):string => $type->getName(), $reflectionTypes);

        return new ReflectionType($typeNames);
    }

    /**
     * @param class-string $className
     *
     * @return ReflectionUseStatement[]
     */
    public static function createReflectionClassUseStatements(string $className): array
    {
        $reflectionClass = new ReflectionClass($className);

        if (!$reflectionClass->getFileName()) {
            throw new ReflectionException('Unable to retrieve filename for class '.$className);
        }

        $source = file_get_contents($reflectionClass->getFileName());
        if (!$source) {
            throw new ReflectionException('Could not open file '.$reflectionClass->getFileName());
        }

        $tokens = token_get_all($source);

        $builtNamespace    = '';
        $useStatements     = [];
        $class             = '';
        $as                = '';
        $buildingNamespace = false;
        $buildingUse       = false;
        $buildingAs        = false;

        foreach ($tokens as $token) {
            if (is_array($token)) {
                if (T_NAMESPACE === $token[0]) {
                    $buildingNamespace = true;
                }

                if (T_USE === $token[0]) {
                    $buildingUse = true;
                }

                if (T_AS === $token[0]) {
                    $buildingAs = true;
                }
            }

            if (';' === $token) {
                $buildingNamespace = false;
                $buildingUse       = false;
                $buildingAs        = false;

                if ($builtNamespace) {
                    $builtNamespace = trim($builtNamespace);
                }

                if ($class) {
                    /** @var class-string $class */
                    $class           = trim($class);
                    $as              = $as ? trim($as) : $class;
                    $useStatements[] = new ReflectionUseStatement($class, $as);
                    $class           = '';
                    $as              = '';
                }
            }

            if ($buildingNamespace) {
                if (T_NAMESPACE === $token[0]) {
                    continue;
                }

                $builtNamespace .= $token[1];

                continue;
            }

            if ($buildingUse) {
                if (T_USE === $token[0]) {
                    continue;
                }

                if (T_AS === $token[0]) {
                    continue;
                }

                if (!$buildingAs) {
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

        $maybeClass = $reflectionClass->getNamespaceName().'\\'.$typeName;
        if (class_exists($maybeClass)) {
            return new ReflectionType([$maybeClass]);
        }

        return null;
    }
}
