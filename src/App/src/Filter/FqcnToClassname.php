<?php

declare(strict_types=1);

namespace App\Filter;

use Laminas\Filter\AbstractFilter;
use Webmozart\Assert\Assert;

final class FqcnToClassname extends AbstractFilter
{

    public function filter($value)
    {
        Assert::allStringNotEmpty($value, '$value to filter must be a non empty string');
        $class = array_slice(explode('\\', $value), -1, 1)[0];
        return $class;
    }
}
