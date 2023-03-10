<?php

declare(strict_types=1);

namespace WebFu\Tests\Fixtures;

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

    public function noDocComments(string $noDocComments): void
    {
    }
}
