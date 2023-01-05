<?php

declare (strict_types=1);
namespace OM4\CopyCraft\Vendor\League\Container\Argument;

use OM4\CopyCraft\Vendor\League\Container\ContainerAwareInterface;
use ReflectionFunctionAbstract;
interface ArgumentResolverInterface extends ContainerAwareInterface
{
    public function resolveArguments(array $arguments) : array;
    public function reflectArguments(ReflectionFunctionAbstract $method, array $args = []) : array;
}
