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

use Attribute;

class ReflectionAttribute extends \ReflectionAttribute
{
    public const TARGET_CLASS          = Attribute::TARGET_CLASS;
    public const TARGET_FUNCTION       = Attribute::TARGET_FUNCTION;
    public const TARGET_METHOD         = Attribute::TARGET_METHOD;
    public const TARGET_PROPERTY       = Attribute::TARGET_PROPERTY;
    public const TARGET_CLASS_CONSTANT = Attribute::TARGET_CLASS_CONSTANT;
    public const TARGET_PARAMETER      = Attribute::TARGET_PARAMETER;
    public const TARGET_CONSTANT       = 64;
    public const TARGET_ALL            = Attribute::TARGET_ALL;

    private \ReflectionAttribute $reflectionAttribute;

    public function __construct(object|string $objectOrClass)
    {
        assert($objectOrClass instanceof \ReflectionAttribute);

        $this->reflectionAttribute = $objectOrClass;
    }

    public function getTarget(): int
    {
        return $this->reflectionAttribute->getTarget();
    }

    public function getName(): string
    {
        return $this->reflectionAttribute->getName();
    }

    public function isRepeated(): bool
    {
        return $this->reflectionAttribute->isRepeated();
    }

    /**
     * @return array<int, mixed>
     */
    public function getArguments(): array
    {
        return $this->reflectionAttribute->getArguments();
    }

    public function newInstance(): object
    {
        return $this->reflectionAttribute->newInstance();
    }
}
