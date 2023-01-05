<?php

declare (strict_types=1);
namespace OM4\CopyCraft\Vendor\League\Container\Exception;

use OM4\CopyCraft\Vendor\Psr\Container\NotFoundExceptionInterface;
use InvalidArgumentException;
class NotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
}
