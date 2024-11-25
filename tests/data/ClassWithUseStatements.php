<?php

declare(strict_types=1);

namespace WebFu\Reflection\Tests\data;

use WebFu\Reflection\Tests\data\GenericClass;
use DateTime as DT;

class ClassWithUseStatements
{
    public DT $dateTime;
    public GenericClass $genericClass;
}
