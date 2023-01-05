<?php

namespace OM4\CopyCraft\Vendor\Http\Discovery\Strategy;

use OM4\CopyCraft\Vendor\Http\Client\HttpAsyncClient;
use OM4\CopyCraft\Vendor\Http\Client\HttpClient;
use OM4\CopyCraft\Vendor\Http\Mock\Client as Mock;
/**
 * Find the Mock client.
 *
 * @author Sam Rapaport <me@samrapdev.com>
 */
final class MockClientStrategy implements DiscoveryStrategy
{
    /**
     * {@inheritdoc}
     */
    public static function getCandidates($type)
    {
        if (\is_a(HttpClient::class, $type, \true) || \is_a(HttpAsyncClient::class, $type, \true)) {
            return [['class' => Mock::class, 'condition' => Mock::class]];
        }
        return [];
    }
}
