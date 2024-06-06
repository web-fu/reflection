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

class ReflectionObject extends ReflectionClass
{
    public function __construct(object $objectOrClass)
    {
        parent::__construct($objectOrClass);
    }
}
