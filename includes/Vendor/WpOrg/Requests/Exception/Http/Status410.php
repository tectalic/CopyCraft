<?php

/**
 * Exception for 410 Gone responses
 *
 * @package Requests\Exceptions
 */
namespace OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;

use OM4\CopyCraft\Vendor\WpOrg\Requests\Exception\Http;
/**
 * Exception for 410 Gone responses
 *
 * @package Requests\Exceptions
 */
final class Status410 extends Http
{
    /**
     * HTTP status code
     *
     * @var integer
     */
    protected $code = 410;
    /**
     * Reason phrase
     *
     * @var string
     */
    protected $reason = 'Gone';
}
