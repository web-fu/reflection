<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use WebFu\Reflection\ReflectionClass;

class Process
{
    private string $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}

$reflection = new ReflectionClass(Process::class);
$reflection->getProperties();

// $process is recognised by static analysis as an object of class Process
$process = $reflection->newInstance();
$process->setId('123');

echo $process->getId();
echo PHP_EOL;