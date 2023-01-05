<?php

namespace OM4\CopyCraft\Vendor\Http\Discovery\Strategy;

use OM4\CopyCraft\Vendor\Psr\Http\Message\RequestFactoryInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\ResponseFactoryInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\ServerRequestFactoryInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\StreamFactoryInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\UploadedFileFactoryInterface;
use OM4\CopyCraft\Vendor\Psr\Http\Message\UriFactoryInterface;
/**
 * @internal
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class CommonPsr17ClassesStrategy implements DiscoveryStrategy
{
    /**
     * @var array
     */
    private static $classes = [RequestFactoryInterface::class => ['OM4\\CopyCraft\\Vendor\\Phalcon\\Http\\Message\\RequestFactory', 'OM4\\CopyCraft\\Vendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'OM4\\CopyCraft\\Vendor\\Zend\\Diactoros\\RequestFactory', 'OM4\\CopyCraft\\Vendor\\GuzzleHttp\\Psr7\\HttpFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Diactoros\\RequestFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Guzzle\\RequestFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Slim\\RequestFactory', 'OM4\\CopyCraft\\Vendor\\Laminas\\Diactoros\\RequestFactory', 'OM4\\CopyCraft\\Vendor\\Slim\\Psr7\\Factory\\RequestFactory'], ResponseFactoryInterface::class => ['OM4\\CopyCraft\\Vendor\\Phalcon\\Http\\Message\\ResponseFactory', 'OM4\\CopyCraft\\Vendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'OM4\\CopyCraft\\Vendor\\Zend\\Diactoros\\ResponseFactory', 'OM4\\CopyCraft\\Vendor\\GuzzleHttp\\Psr7\\HttpFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Diactoros\\ResponseFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Guzzle\\ResponseFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Slim\\ResponseFactory', 'OM4\\CopyCraft\\Vendor\\Laminas\\Diactoros\\ResponseFactory', 'OM4\\CopyCraft\\Vendor\\Slim\\Psr7\\Factory\\ResponseFactory'], ServerRequestFactoryInterface::class => ['OM4\\CopyCraft\\Vendor\\Phalcon\\Http\\Message\\ServerRequestFactory', 'OM4\\CopyCraft\\Vendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'OM4\\CopyCraft\\Vendor\\Zend\\Diactoros\\ServerRequestFactory', 'OM4\\CopyCraft\\Vendor\\GuzzleHttp\\Psr7\\HttpFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Diactoros\\ServerRequestFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Guzzle\\ServerRequestFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Slim\\ServerRequestFactory', 'OM4\\CopyCraft\\Vendor\\Laminas\\Diactoros\\ServerRequestFactory', 'OM4\\CopyCraft\\Vendor\\Slim\\Psr7\\Factory\\ServerRequestFactory'], StreamFactoryInterface::class => ['OM4\\CopyCraft\\Vendor\\Phalcon\\Http\\Message\\StreamFactory', 'OM4\\CopyCraft\\Vendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'OM4\\CopyCraft\\Vendor\\Zend\\Diactoros\\StreamFactory', 'OM4\\CopyCraft\\Vendor\\GuzzleHttp\\Psr7\\HttpFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Diactoros\\StreamFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Guzzle\\StreamFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Slim\\StreamFactory', 'OM4\\CopyCraft\\Vendor\\Laminas\\Diactoros\\StreamFactory', 'OM4\\CopyCraft\\Vendor\\Slim\\Psr7\\Factory\\StreamFactory'], UploadedFileFactoryInterface::class => ['OM4\\CopyCraft\\Vendor\\Phalcon\\Http\\Message\\UploadedFileFactory', 'OM4\\CopyCraft\\Vendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'OM4\\CopyCraft\\Vendor\\Zend\\Diactoros\\UploadedFileFactory', 'OM4\\CopyCraft\\Vendor\\GuzzleHttp\\Psr7\\HttpFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Diactoros\\UploadedFileFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Guzzle\\UploadedFileFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Slim\\UploadedFileFactory', 'OM4\\CopyCraft\\Vendor\\Laminas\\Diactoros\\UploadedFileFactory', 'OM4\\CopyCraft\\Vendor\\Slim\\Psr7\\Factory\\UploadedFileFactory'], UriFactoryInterface::class => ['OM4\\CopyCraft\\Vendor\\Phalcon\\Http\\Message\\UriFactory', 'OM4\\CopyCraft\\Vendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'OM4\\CopyCraft\\Vendor\\Zend\\Diactoros\\UriFactory', 'OM4\\CopyCraft\\Vendor\\GuzzleHttp\\Psr7\\HttpFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Diactoros\\UriFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Guzzle\\UriFactory', 'OM4\\CopyCraft\\Vendor\\Http\\Factory\\Slim\\UriFactory', 'OM4\\CopyCraft\\Vendor\\Laminas\\Diactoros\\UriFactory', 'OM4\\CopyCraft\\Vendor\\Slim\\Psr7\\Factory\\UriFactory']];
    /**
     * {@inheritdoc}
     */
    public static function getCandidates($type)
    {
        $candidates = [];
        if (isset(self::$classes[$type])) {
            foreach (self::$classes[$type] as $class) {
                $candidates[] = ['class' => $class, 'condition' => [$class]];
            }
        }
        return $candidates;
    }
}
