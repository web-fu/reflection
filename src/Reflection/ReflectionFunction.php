<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionFunction extends ReflectionFunctionAbstract
{
    public function __construct(\Closure|string $function)
    {
        $this->reflectionFunction = new \ReflectionFunction($function);
    }

    public function getClosure(): \Closure|null
    {
        assert($this->reflectionFunction instanceof \ReflectionFunction);

        return $this->reflectionFunction->getClosure();
    }

    public function invoke(mixed ...$args): mixed
    {
        assert($this->reflectionFunction instanceof \ReflectionFunction);

        return $this->reflectionFunction->invoke(...$args);
    }

    /**
     * @param mixed[] $args
     */
    public function invokeArgs(array $args): mixed
    {
        assert($this->reflectionFunction instanceof \ReflectionFunction);

        return $this->reflectionFunction->invokeArgs($args);
    }

    public function isAnonymous(): bool
    {
        assert($this->reflectionFunction instanceof \ReflectionFunction);

        if (PHP_VERSION_ID < 80200) {
            throw new ReflectionException('isAnonymous() is only available in PHP 8.2 or higher.');
        }
        return $this->reflectionFunction->isAnonymous();
    }

    public function getParameters(): array
    {
        return array_map(fn (\ReflectionParameter $reflectionParameter): ReflectionParameter => new ReflectionParameter($reflectionParameter->getDeclaringFunction()->getName(), $reflectionParameter->getName()), $this->reflectionFunction->getParameters());
    }

    public function __toString(): string
    {
        return $this->reflectionFunction->__toString();
    }
}
