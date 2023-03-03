<?php declare(strict_types = 1);

$includes = [];
if (PHP_VERSION_ID < 80100) {
    $includes[] = __DIR__ . '/readonly-property.neon';
    $includes[] = __DIR__ . '/enum.neon';
    $includes[] = __DIR__ . '/final.neon';
}

$config = [];
$config['includes'] = $includes;
$config['parameters']['phpVersion'] = PHP_VERSION_ID;

return $config;
