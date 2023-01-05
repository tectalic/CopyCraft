<?php

declare (strict_types=1);
namespace OM4\CopyCraft\Vendor\Nyholm\Psr7\Factory;

use OM4\CopyCraft\Vendor\Http\Message\MessageFactory;
use OM4\CopyCraft\Vendor\Http\Message\StreamFactory;
use OM4\CopyCraft\Vendor\Http\Message\UriFactory;
use OM4\CopyCraft\Vendor\Nyholm\Psr7\Request;
use OM4\CopyCraft\Vendor\Nyholm\Psr7\Response;
use OM4\CopyCraft\Vendor\Nyholm\Psr7\Stream;
use OM4\CopyCraft\Vendor\Nyholm\Psr7\Uri;
use OM4\CopyCraft\Vendor\Psr\Http\Message\RequestInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\ResponseInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\StreamInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\UriInterface;
/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Martijn van der Ven <martijn@vanderven.se>
 *
 * @final This class should never be extended. See https://github.com/Nyholm/psr7/blob/master/doc/final.md
 */
class HttplugFactory implements MessageFactory, StreamFactory, UriFactory
{
    public function createRequest($method, $uri, array $headers = [], $body = null, $protocolVersion = '1.1') : RequestInterface
    {
        return new Request($method, $uri, $headers, $body, $protocolVersion);
    }
    public function createResponse($statusCode = 200, $reasonPhrase = null, array $headers = [], $body = null, $version = '1.1') : ResponseInterface
    {
        return new Response((int) $statusCode, $headers, $body, $version, $reasonPhrase);
    }
    public function createStream($body = null) : StreamInterface
    {
        return Stream::create($body ?? '');
    }
    public function createUri($uri = '') : UriInterface
    {
        if ($uri instanceof UriInterface) {
            return $uri;
        }
        return new Uri($uri);
    }
}
