<?php

declare(strict_types=1);

namespace WebFu\Reflection;

class ReflectionUseStatement
{
    /**
     * @param class-string $className
     */
    public function __construct(private string $className, private string $as)
    {
    }

    /**
     * @return class-string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    public function getAs(): string
    {
        return $this->as;
    }
}
