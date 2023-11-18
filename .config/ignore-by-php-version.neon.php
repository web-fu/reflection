<?php declare(strict_types = 1);

$includes = [];
if (PHP_VERSION_ID < 80100) {
    $includes[] = __DIR__ . '/enum.neon';
    $includes[] = __DIR__ . '/final.neon';
    $includes[] = __DIR__ . '/reflection-function-abstract.neon';
    $includes[] = __DIR__ . '/reflection-property-readonly.neon';
}

if (PHP_VERSION_ID < 80200) {
    $includes[] = __DIR__ . '/reflection-class-readonly.neon';
    $includes[] = __DIR__ . '/reflection-function.neon';
    $includes[] = __DIR__ . '/reflection-function-abstract-prototype.neon';
}
$config = [];
$config['includes'] = $includes;
$config['parameters']['phpVersion'] = PHP_VERSION_ID;

return $config;
