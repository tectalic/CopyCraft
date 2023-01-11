<?php

/**
 * Exception for 511 Network Authentication Required responses
 *
 * @link https://tools.ietf.org/html/rfc6585
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 511 Network Authentication Required responses
 *
 * @link https://tools.ietf.org/html/rfc6585
 *
 * @package Requests\Exceptions
 */
final class Status511 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 511;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Network Authentication Required';
}
