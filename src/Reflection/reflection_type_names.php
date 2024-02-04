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
use ReflectionType;
use ReflectionUnionType;

if (!function_exists(__NAMESPACE__.'\reflection_type_names')) {
    /**
     * @internal
     *
     * @return string[]
     */
    function reflection_type_names(ReflectionType|ReflectionNamedType|ReflectionUnionType|null $reflectionType): array
    {
        if (!$reflectionType) {
            return ['mixed'];
        }

        if (PHP_VERSION_ID >= 80000 && $reflectionType instanceof \ReflectionIntersectionType) {
            $reflectionNamedTypes = $reflectionType->getTypes();
            $typeNames = array_map(fn (ReflectionNamedType $type): string => $type->getName(), $reflectionNamedTypes);

            sort($typeNames);

            return [implode('&', $typeNames)];
        }

        /** @var ReflectionNamedType[] $reflectionNamedTypes */
        $reflectionNamedTypes = $reflectionType instanceof ReflectionUnionType
            ? $reflectionType->getTypes()
            : [$reflectionType];

        $typeNames = array_map(fn (ReflectionNamedType $type): string => $type->getName(), $reflectionNamedTypes);

        if (in_array('mixed', $typeNames, true)) {
            return ['mixed'];
        }

        foreach ($reflectionNamedTypes as $reflectionNamedType) {
            if ($reflectionNamedType->allowsNull()) {
                $typeNames[] = 'null';
                break;
            }
        }

        sort($typeNames);

        return $typeNames;
    }
}
