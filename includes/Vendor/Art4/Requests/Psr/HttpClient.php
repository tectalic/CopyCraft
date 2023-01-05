<?php

declare (strict_types=1);
/**
 * Implementation for PSR-18 HTTP client and some PSR-17 factories
 */
namespace OM4\CopyCraft\Vendor\Art4\Requests\Psr;

use OM4\CopyCraft\Vendor\Art4\Requests\Exception\Psr\NetworkException;
use OM4\CopyCraft\Vendor\Art4\Requests\Exception\Psr\RequestException;
use Exception as GlobalException;
use OM4\CopyCraft\Vendor\Psr\Http\Client\ClientInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\RequestFactoryInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\RequestInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\ResponseInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\StreamFactoryInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\StreamInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\UriInterface;
use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception;
use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\InvalidArgument;
use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Transport;
use OM4\CopyCraft\Vendor\WpOrg\Requests\Iri;
use OM4\CopyCraft\Vendor\WpOrg\Requests\Requests;
/**
 * HTTP implementation for PSR-17 and PSR-18
 */
final class HttpClient implements RequestFactoryInterface, StreamFactoryInterface, ClientInterface
{
    /**
     * @var array
     */
    private $options = [];
    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }
    /**
     * Create a new request.
     *
     * @param string $method The HTTP method associated with the request.
     * @param UriInterface|string $uri The URI associated with the request.
     */
    public function createRequest(string $method, $uri) : RequestInterface
    {
        if (!$uri instanceof UriInterface) {
            if (!\is_string($uri)) {
                throw InvalidArgument::create(2, '$uri', UriInterface::class . '|string', \gettype($uri));
            }
            $uri = Uri::fromIri(new Iri($uri));
        }
        return Request::withMethodAndUri($method, $uri);
    }
    /**
     * Create a new stream from a string.
     *
     * The stream SHOULD be created with a temporary resource.
     *
     * @param string $content String content with which to populate the stream.
     * @return StreamInterface
     */
    public function createStream(string $content = '') : StreamInterface
    {
        if (!\is_string($content)) {
            throw InvalidArgument::create(1, '$content', 'string', \gettype($content));
        }
        return StringBasedStream::createFromString($content);
    }
    /**
     * Sends a PSR-7 request and returns a PSR-7 response.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     */
    public function sendRequest(RequestInterface $request) : ResponseInterface
    {
        $headers = [];
        foreach ($request->getHeaders() as $key => $header) {
            $headers[$key] = $request->getHeaderLine($key);
        }
        try {
            $response = Requests::request($request->getUri()->__toString(), $headers, $request->getBody()->__toString(), $request->getMethod(), $this->options);
        } catch (Transport $th) {
            throw new NetworkException($request, $th);
        } catch (Exception $th) {
            throw new RequestException($request, $th);
        }
        return Response::fromResponse($response);
    }
    /**
     * Create a stream from an existing file.
     *
     * The file MUST be opened using the given mode, which may be any mode
     * supported by the `fopen` function.
     *
     * The `$filename` MAY be any string supported by `fopen()`.
     *
     * @param string $filename Filename or stream URI to use as basis of stream.
     * @param string $mode Mode with which to open the underlying filename/stream.
     *
     * @return StreamInterface
     * @throws \RuntimeException If the file cannot be opened.
     * @throws \InvalidArgumentException If the mode is invalid.
     */
    public function createStreamFromFile(string $filename, string $mode = 'r') : StreamInterface
    {
        throw new GlobalException(__METHOD__ . '() is not yet implemented.');
    }
    /**
     * Create a new stream from an existing resource.
     *
     * The stream MUST be readable and may be writable.
     *
     * @param resource $resource PHP resource to use as basis of stream.
     *
     * @return StreamInterface
     */
    public function createStreamFromResource($resource) : StreamInterface
    {
        throw new GlobalException(__METHOD__ . '() is not yet implemented.');
    }
}
