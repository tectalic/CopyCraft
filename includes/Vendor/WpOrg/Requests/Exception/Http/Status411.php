<?php

/**
 * Exception for 411 Length Required responses
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 411 Length Required responses
 *
 * @package Requests\Exceptions
 */
final class Status411 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 411;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Length Required';
}
