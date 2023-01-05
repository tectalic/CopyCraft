<?php

declare (strict_types=1);
namespace OM4\CopyCraft\Vendor\League\Container;

use OM4\CopyCraft\Vendor\League\Container\Definition\DefinitionInterface;
use OM4\CopyCraft\Vendor\League\Container\Inflector\InflectorInterface;
use OM4\CopyCraft\Vendor\League\Container\ServiceProvider\ServiceProviderInterface;
use OM4\CopyCraft\Vendor\Psr\Container\ContainerInterface;
interface DefinitionContainerInterface extends ContainerInterface
{
    public function add(string $id, $concrete = null) : DefinitionInterface;
    public function addServiceProvider(ServiceProviderInterface $provider) : self;
    public function addShared(string $id, $concrete = null) : DefinitionInterface;
    public function extend(string $id) : DefinitionInterface;
    public function getNew($id);
    public function inflector(string $type, callable $callback = null) : InflectorInterface;
}
