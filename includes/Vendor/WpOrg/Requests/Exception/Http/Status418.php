<?php

/**
 * Exception for 418 I'm A Teapot responses
 *
 * @link https://tools.ietf.org/html/rfc2324
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 418 I'm A Teapot responses
 *
 * @link https://tools.ietf.org/html/rfc2324
 *
 * @package Requests\Exceptions
 */
final class Status418 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 418;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = "I'm A Teapot";
}
