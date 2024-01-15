<?php

declare(strict_types=1);

namespace WebFu\Tests\Fixtures;

class ClassWithConstants
{
    public const PUBLIC = 1;
    protected const PROTECTED = 2;
    private const PRIVATE = 3;

    #[Attribute]
    public const PUBLIC_WITH_ATTRIBUTE = 4;

    /**
     * Doc comment
     */
    public const PUBLIC_WITH_DOC_COMMENT = 5;
}
