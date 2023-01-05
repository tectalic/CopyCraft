<?php

namespace OM4\CopyCraft\Vendor\Http\Discovery;

use OM4\CopyCraft\Vendor\Http\Discovery\Exception\DiscoveryFailedException;
use OM4\CopyCraft\Vendor\Http\Message\MessageFactory;
/**
 * Finds a Message Factory.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @deprecated This will be removed in 2.0. Consider using Psr17FactoryDiscovery.
 */
final class MessageFactoryDiscovery extends ClassDiscovery
{
    /**
     * Finds a Message Factory.
     *
     * @return MessageFactory
     *
     * @throws Exception\NotFoundException
     */
    public static function find()
    {
        try {
            $messageFactory = static::findOneByType(MessageFactory::class);
        } catch (DiscoveryFailedException $e) {
            throw new NotFoundException('No message factories found. To use Guzzle, Diactoros or Slim Framework factories install php-http/message and the chosen message implementation.', 0, $e);
        }
        return static::instantiateClass($messageFactory);
    }
}
