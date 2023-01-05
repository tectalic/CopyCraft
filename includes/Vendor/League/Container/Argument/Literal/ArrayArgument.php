<?php

declare (strict_types=1);
namespace OM4\CopyCraft\Vendor\League\Container\Argument\Literal;

use OM4\CopyCraft\Vendor\League\Container\Argument\LiteralArgument;
class ArrayArgument extends LiteralArgument
{
    public function __construct(array $value)
    {
        parent::__construct($value, LiteralArgument::TYPE_ARRAY);
    }
}
