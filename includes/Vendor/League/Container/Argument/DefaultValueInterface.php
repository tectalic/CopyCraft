<?php

declare (strict_types=1);
namespace OM4\CopyCraft\Vendor\League\Container\Argument;

interface DefaultValueInterface extends ArgumentInterface
{
    /**
     * @return mixed
     */
    public function getDefaultValue();
}
