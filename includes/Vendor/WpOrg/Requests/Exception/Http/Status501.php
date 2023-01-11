<?php

/**
 * Exception for 501 Not Implemented responses
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 501 Not Implemented responses
 *
 * @package Requests\Exceptions
 */
final class Status501 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 501;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Not Implemented';
}
