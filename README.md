Reflection
==============================================================================================
[![Latest Stable Version](https://poser.pugx.org/web-fu/reflection/v)](https://packagist.org/packages/web-fu/reflection)
[![PHP Version Require](https://poser.pugx.org/web-fu/reflection/require/php)](https://packagist.org/packages/web-fu/reflection)
![Test status](https://github.com/web-fu/reflection/actions/workflows/tests.yaml/badge.svg)
![Static analysis status](https://github.com/web-fu/reflection/actions/workflows/static-analysis.yml/badge.svg)
![Code style status](https://github.com/web-fu/reflection/actions/workflows/code-style.yaml/badge.svg)

### This library is a type safe wrapper for PHP Reflection API.

This library is born with the purpose to solve the problem of type safety in PHP Reflection API.
Reflection API is a very powerful tool, but presents some issues.

For example:
- the original `ReflectionClass::getConstant` return `false` if the constant does not exist or if the constant is equal to `false`.
- `ReflectionClass::newInstance` return a generic object, but it is possible to know the type of the object.
- New interfaces are added to the Reflection API in different PHP versions, so it is not possible to use them in a cross-version way.

## Installation

web-fu/reflection is available on [Packagist](https://packagist.org/packages/web-fu/reflection) and can be installed
using [Composer](https://getcomposer.org/).

```bash
composer require web-fu/reflection
```
> Requires PHP 8.0 or newer.

## Usage
This wrapper try to use the same names of the original Reflection API, but with a different namespace.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use WebFu\Reflection\ReflectionClass;
use MyNamespace\MyClass;

$reflection = new ReflectionClass(MyClass::class);
echo $reflection->getName(); // MyNamespace\MyClass
echo $reflection->getShortName(); // MyClass
```

## Type management
PHP Reflection API use different classes to manage types: `ReflectionType`, `ReflectionNamedType` and `ReflectionUnionType`.

I created a single class to manage all types: `WebFu\Reflection\ReflectionType`.
I added a helper function to infer the PHPDocType, if specified

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use WebFu\Reflection\ReflectionClass;

class ClassWithTypes
{
    public int $simple;
    public int|string $union;
    public $noType;
    public ?int $nullable;
    /** @var class-string */
    public string $className;
}

$reflection = new ReflectionClass(MyClass::class);
echo $reflection->getProperty('simple')->getType()->getTypeNames();         // ['int']
echo $reflection->getProperty('union')->getType()->getTypeNames();          // ['int','string']
echo $reflection->getProperty('noType')->getType()->getTypeNames();         // ['mixed']
echo $reflection->getProperty('nullable')->getType()->getTypeNames();       // ['int','null']
echo $reflection->getProperty('nullable')->getType()->getPhpDocTypeNames(); // ['class-string']
```
