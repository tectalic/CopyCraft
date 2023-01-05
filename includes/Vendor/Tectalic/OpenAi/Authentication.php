<?php

/**
 * Copyright (c) 2022 Tectalic (https://tectalic.com)
 *
 * For copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * Please see the README.md file for usage instructions.
 */
declare (strict_types=1);
namespace OM4\CopyCraft\Vendor\Tectalic\OpenAi;

use OM4\CopyCraft\Vendor\Http\Message\Authentication as Authentication1;
use OM4\CopyCraft\Vendor\Http\Message\Authentication\Bearer;
use OM4\CopyCraft\Vendor\Psr\Http\Message\RequestInterface;
final class Authentication implements Authentication1
{
    /** @var Bearer */
    private $auth;
    /**
     * Authenticate a request with HTTP Bearer authentication.
     *
     * @param string $token Token
     */
    public function __construct(string $token)
    {
        $this->auth = new \OM4\CopyCraft\Vendor\Http\Message\Authentication\Bearer($token);
    }
    /**
     * Alter the request to add the authentication credentials.
     */
    public function authenticate(RequestInterface $request) : RequestInterface
    {
        return $this->auth->authenticate($request);
    }
}
