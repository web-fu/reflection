<?php

declare(strict_types=1);

namespace WebFu\Reflection\Tests\Fixtures;

use WebFu\Reflection\Tests\Fixtures\GenericClass;
use DateTime as DT;

class ClassWithUseStatements
{
    public DT $dateTime;
    public GenericClass $genericClass;
}
