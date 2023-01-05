<?php

namespace OM4\CopyCraft\Vendor\Http\Message\Authentication;

use OM4\CopyCraft\Vendor\Http\Message\Authentication;
use OM4\CopyCraft\Vendor\Psr\Http\Message\RequestInterface;
class Header implements Authentication
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string|string[]
     */
    private $value;
    /**
     * @param string|string[] $value
     */
    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
    /**
     * {@inheritdoc}
     */
    public function authenticate(RequestInterface $request)
    {
        return $request->withHeader($this->name, $this->value);
    }
}
