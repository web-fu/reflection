<?php

declare(strict_types=1);

namespace WebFu\Tests\Fixtures;

use WebFu\Tests\Fixtures\GenericClass as GC;

/**
 * @template Test
 */
class ClassWithDocComments
{
    /**
     * @depends-annotations Test
     * @var class-string
     */
    private string $property;
    private string $noDocComments;

    /** @var GC[] */
    private array $useStatementDocComment;

    private GenericClass $genericClass;

    /**
     * @depends-annotations Test
     * @return class-string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @param class-string $property
     * @return $this
     */
    public function setProperty(string $property): self
    {
        $this->property = $property;

        return $this;
    }

    /**
     * @return array<GC>
     */
    public function getUseStatementDocComment()
    {
        return $this->useStatementDocComment;
    }

    /**
     * @param array<GC> $param
     */
    public function setUseStatementDocComment(array $param): void
    {
        $this->useStatementDocComment = $param;
    }

    public function noDocComments(string $noDocComments): void
    {
    }
}
