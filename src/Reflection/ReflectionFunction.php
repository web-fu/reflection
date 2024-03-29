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

use Closure;

class ReflectionFunction extends ReflectionFunctionAbstract
{
    public function __construct(Closure|string $function)
    {
        $this->reflectionFunction = new \ReflectionFunction($function);
    }

    public function __toString(): string
    {
        return $this->reflectionFunction->__toString();
    }

    /**
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'name'        => $this->getName(),
            'attributes'  => $this->getAttributes(),
            'annotations' => $this->getAnnotations(),
        ];
    }

    public function getClosure(): Closure|null
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionFunction);

        return $this->reflectionFunction->getClosure();
    }

    public function invoke(mixed ...$args): mixed
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionFunction);

        return $this->reflectionFunction->invoke(...$args);
    }

    /**
     * @param mixed[] $args
     */
    public function invokeArgs(array $args): mixed
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionFunction);

        return $this->reflectionFunction->invokeArgs($args);
    }

    public function isAnonymous(): bool
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionFunction);

        if (PHP_VERSION_ID < 80200) {
            throw new WrongPhpVersionException('Anonymous functions are not available for PHP versions lower than 8.2.0');
        }

        return $this->reflectionFunction->isAnonymous();
    }

    /**
     * @return ReflectionParameter[]
     */
    public function getParameters(): array
    {
        return array_map(fn (\ReflectionParameter $reflectionParameter): ReflectionParameter => new ReflectionParameter($reflectionParameter->getDeclaringFunction()->getName(), $reflectionParameter->getName()), $this->reflectionFunction->getParameters());
    }

    /**
     * @return string[]
     */
    public function getPhpDocReturnTypeNames(): array
    {
        return [];
    }
}
