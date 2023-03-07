<?php

declare(strict_types=1);

namespace WebFu\Reflection;

abstract class AbstractReflection
{
    abstract public function getDocComment(): string|null;

    abstract public function __toString(): string;

    /**
     * @return string[]
     */
    public function getAnnotations(): array
    {
        /** @var string $docComment */
        $docComment = preg_replace('#^\s*/\*\*([^/]+)\*/\s*$#', '$1', $this->getDocComment() ?: '');
        $docComment = preg_replace('/\R/', PHP_EOL, $docComment);

        /** @phpstan-ignore-next-line */
        $sanitized = trim(preg_replace('/^\s*\*\s*(.+)/m', '$1', $docComment));

        return explode(PHP_EOL, $sanitized);
    }
}
