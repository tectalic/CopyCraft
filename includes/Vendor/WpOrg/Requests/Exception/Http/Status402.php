<?php

/**
 * Exception for 402 Payment Required responses
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 402 Payment Required responses
 *
 * @package Requests\Exceptions
 */
final class Status402 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 402;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Payment Required';
}
