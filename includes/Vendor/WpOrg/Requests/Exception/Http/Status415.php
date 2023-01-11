<?php

/**
 * Exception for 415 Unsupported Media Type responses
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 415 Unsupported Media Type responses
 *
 * @package Requests\Exceptions
 */
final class Status415 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 415;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Unsupported Media Type';
}
