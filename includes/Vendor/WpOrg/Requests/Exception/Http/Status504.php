<?php

/**
 * Exception for 504 Gateway Timeout responses
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 504 Gateway Timeout responses
 *
 * @package Requests\Exceptions
 */
final class Status504 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 504;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Gateway Timeout';
}
