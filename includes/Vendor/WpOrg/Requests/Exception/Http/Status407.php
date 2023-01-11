<?php

/**
 * Exception for 407 Proxy Authentication Required responses
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 407 Proxy Authentication Required responses
 *
 * @package Requests\Exceptions
 */
final class Status407 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 407;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Proxy Authentication Required';
}
