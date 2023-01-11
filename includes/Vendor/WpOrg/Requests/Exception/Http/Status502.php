<?php

/**
 * Exception for 502 Bad Gateway responses
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 502 Bad Gateway responses
 *
 * @package Requests\Exceptions
 */
final class Status502 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 502;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Bad Gateway';
}
