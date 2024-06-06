<?php

namespace WebFu\Reflection;

class ReflectionObject extends ReflectionClass
{
    public function __construct(object $objectOrClass)
    {
        parent::__construct($objectOrClass);
    }
}