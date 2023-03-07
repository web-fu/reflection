<?php

declare(strict_types=1);

namespace WebFu\Reflection;

abstract class AbstractReflection
{
    abstract public function getDocComment(): string|null;

    abstract public function __toString(): string;

    public function sanitizeDocBlock(): string
    {
        /** @var string $docComment */
        $docComment = preg_replace('#^\s*/\*\*([^/]+)\*/\s*$#', '$1', $this->getDocComment() ?: '');
        $docComment = preg_replace('/\R/', PHP_EOL, $docComment);

        /** @phpstan-ignore-next-line */
        return trim(preg_replace('/^\s*\*\s*(.+)/m', '$1', $docComment));
    }
}
