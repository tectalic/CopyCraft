<?php

/**
 * Exception for 408 Request Timeout responses
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 408 Request Timeout responses
 *
 * @package Requests\Exceptions
 */
final class Status408 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 408;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Request Timeout';
}
