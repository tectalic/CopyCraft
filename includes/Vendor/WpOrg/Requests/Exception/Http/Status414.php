<?php

/**
 * Exception for 414 Request-URI Too Large responses
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 414 Request-URI Too Large responses
 *
 * @package Requests\Exceptions
 */
final class Status414 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 414;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Request-URI Too Large';
}
