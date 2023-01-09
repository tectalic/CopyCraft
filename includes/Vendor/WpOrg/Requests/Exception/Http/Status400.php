<?php

/**
 * Exception for 400 Bad Request responses
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 400 Bad Request responses
 *
 * @package Requests\Exceptions
 */
final class Status400 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 400;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Bad Request';
}
