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

        return PHP_VERSION_ID >= 80100 && $this->reflectionFunction->isAnonymous();
    }

    public function __toString(): string
    {
        return $this->reflectionFunction->__toString();
    }
}