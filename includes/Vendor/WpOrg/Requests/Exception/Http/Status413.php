<?php

/**
 * Exception for 413 Request Entity Too Large responses
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 413 Request Entity Too Large responses
 *
 * @package Requests\Exceptions
 */
final class Status413 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 413;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Request Entity Too Large';
}
