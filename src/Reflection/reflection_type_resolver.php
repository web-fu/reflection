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

/**
 * @param class-string $className
 */
function reflection_type_resolver(string $className, string $typeName): ReflectionType|null
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
