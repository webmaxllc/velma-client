<?php

namespace Webmax\VelmaClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\ClientInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializerBuilder;

/**
 * Velma API client
 *
 * @author Frank Bardon Jr. <frankbardon@gmail.com>
 */
class VelmaClient
{
    /**
     * Guzzle HTTP client
     *
     * @var GuzzleClient
     */
    private $client;

    /**
     * Are we in debug mode?
     *
     * @var boolean
     */
    private $debug;

    /**
     * JMS Serializer
     *
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * JMS Serializer cache directory
     *
     * @var string
     */
    private $serializerCacheDirectory;
}
