<?php

namespace OM4\CopyCraft\Vendor\Http\Message;

use OM4\CopyCraft\Vendor\Psr\Http\Message\UriInterface;
/**
 * Factory for PSR-7 URI.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface UriFactory
{
    /**
     * Creates an PSR-7 URI.
     *
     * @param string|UriInterface $uri
     *
     * @return UriInterface
     *
     * @throws \InvalidArgumentException If the $uri argument can not be converted into a valid URI.
     */
    public function createUri($uri);
}
