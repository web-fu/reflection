<?php

namespace WebFu\Reflection\Tests\data;

class ClassWithAsymmetricVisibilityProperties
{
    public private(set) string $version = '8.4';

    public function increment(): void
    {
        [$major, $minor] = explode('.', $this->version);
        $minor++;
        $this->version = "{$major}.{$minor}";
    }
}