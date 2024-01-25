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

abstract class AbstractReflection
{
    abstract public function __toString(): string;

    /**
     * @return array<string, mixed>
     */
    abstract public function __debugInfo(): array;

    abstract public function getName(): string;

    abstract public function getDocComment(): string|null;

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

        return array_filter(explode(PHP_EOL, $sanitized));
    }
}
