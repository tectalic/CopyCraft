<?php

/**
 * Exception for 403 Forbidden responses
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 403 Forbidden responses
 *
 * @package Requests\Exceptions
 */
final class Status403 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 403;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Forbidden';
}
