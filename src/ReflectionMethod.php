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

class ReflectionMethod extends ReflectionFunctionAbstract
{
    public const IS_STATIC    = 16;
    public const IS_PUBLIC    = 1;
    public const IS_PROTECTED = 2;
    public const IS_PRIVATE   = 4;
    public const IS_ABSTRACT  = 64;
    public const IS_FINAL     = 32;

    /* Methods */
    public function __construct(object|string $objectOrMethod, string $method)
    {
        $this->reflectionFunction = new \ReflectionMethod($objectOrMethod, $method);
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
            'class'       => $this->getDeclaringClass()->getName(),
            'name'        => $this->getName(),
            'attributes'  => $this->getAttributes(),
            'annotations' => $this->getAnnotations(),
        ];
    }

    public function getClosure(object|null $object = null): Closure
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        if (
            $this->isStatic()
            && null !== $object
        ) {
            throw new ReflectionException('Cannot bind an instance to a static closure');
        }

        if (
            !$this->isStatic()
            && null === $object
        ) {
            throw new ReflectionException('Cannot create closure for method without object');
        }

        $closure = $this->reflectionFunction->getClosure($object);

        if (!$closure instanceof Closure) {
            throw new ReflectionException('Cannot create closure for method');
        }

        return $closure;
    }

    public function getDeclaringClass(): ReflectionClass
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return new ReflectionClass($this->reflectionFunction->getDeclaringClass()->getName());
    }

    public function getModifiers(): int
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->getModifiers();
    }

    /**
     * @return ReflectionParameter[]
     */
    public function getParameters(): array
    {
        $closure = [$this->getDeclaringClass()->getName(), $this->reflectionFunction->getName()];

        return array_map(fn (\ReflectionParameter $reflectionParameter) => new ReflectionParameter($closure, $reflectionParameter->getName()), $this->reflectionFunction->getParameters());
    }

    public function getPrototype(): self
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        $method = $this->reflectionFunction->getPrototype();

        return new self($method->getDeclaringClass()->getName(), $method->getName());
    }

    /**
     * @return string[]
     */
    public function getPhpDocReturnTypeNames(): array
    {
        $docTypes = array_filter($this->getAnnotations(), fn (string $annotation) => str_starts_with($annotation, '@return'));

        if (!count($docTypes)) {
            return [];
        }

        if (count($docTypes) > 1) {
            throw new ReflectionException('Invalid PHPDoc annotation');
        }

        $returnAnnotation = array_pop($docTypes);

        preg_match('/@return\s(?<return>.+)/', $returnAnnotation, $matches);

        $docTypes = $matches['return'] ?? '';

        $docTypesList         = explode('|', $docTypes);
        $docTypesListResolved = [];

        foreach ($docTypesList as $docType) {
            $isArray = false;

            preg_match('/array<(?<group1>[a-z]+)>|(?<group2>[a-z]+)\[]/i', $docType, $matches);

            if ($matches) {
                $docType = $matches['group1'].($matches['group2'] ?? '');
                $isArray = true;
            }

            if ($resolved = reflection_type_resolver($this->getDeclaringClass()->getName(), $docType)) {
                $docType = $resolved->getTypeNames()[0];
            }

            if ($isArray) {
                $docType .= '[]';
            }

            $docTypesListResolved[] = $docType;
        }

        return $docTypesListResolved;
    }

    public function hasPrototype(): bool
    {
        if (PHP_VERSION_ID < 80200) {
            throw new WrongPhpVersionException('hasPrototype() is not available for PHP versions lower than 8.2.0');
        }

        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->hasPrototype();
    }

    public function invoke(object|null $object, mixed ...$args): mixed
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->invoke($object, ...$args);
    }

    /**
     * @param mixed[] $args
     */
    public function invokeArgs(object|null $object, array $args): mixed
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->invokeArgs($object, $args);
    }

    public function isAbstract(): bool
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isAbstract();
    }

    public function isConstructor(): bool
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isConstructor();
    }

    public function isDestructor(): bool
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isDestructor();
    }

    public function isFinal(): bool
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isFinal();
    }

    public function isPrivate(): bool
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isPrivate();
    }

    public function isProtected(): bool
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isProtected();
    }

    public function isPublic(): bool
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        return $this->reflectionFunction->isPublic();
    }

    public function setAccessible(bool $accessible): void
    {
        /*
         * @infection-ignore-all
         */
        assert($this->reflectionFunction instanceof \ReflectionMethod);

        $this->reflectionFunction->setAccessible($accessible);
    }

    public function isMagic(): bool
    {
        return in_array($this->getName(), [
            '__construct',
            '__destruct',
            '__call',
            '__callStatic',
            '__get',
            '__set',
            '__isset',
            '__unset',
            '__sleep',
            '__wakeup',
            '__serialize',
            '__unserialize',
            '__toString',
            '__invoke',
            '__set_state',
            '__clone',
            '__debugInfo',
        ], true);
    }
}
