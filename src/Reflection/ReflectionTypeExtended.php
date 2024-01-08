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

class ReflectionTypeExtended
{
    /**
     * @param string[] $typeNames
     * @param string[] $docBlockTypeNames
     */
    public function __construct(private array $typeNames = [], private array $docBlockTypeNames = [])
    {
    }

    /**
     * @return string[]
     */
    public function getTypeNames(): array
    {
        return $this->typeNames;
    }

    /**
     * @return string[]
     */
    public function getDocBlockTypeNames(): array
    {
        return $this->docBlockTypeNames;
    }
}
