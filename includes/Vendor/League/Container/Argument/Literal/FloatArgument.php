<?php

declare (strict_types=1);
namespace OM4\CopyCraft\Vendor\League\Container\Argument\Literal;

use OM4\CopyCraft\Vendor\League\Container\Argument\LiteralArgument;
class FloatArgument extends LiteralArgument
{
    public function __construct(float $value)
    {
        parent::__construct($value, LiteralArgument::TYPE_FLOAT);
    }
}
