<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use Webmax\VelmaClient\VelmaClient;

abstract class ClientTestCase extends PHPUnit_Framework_TestCase
{
    const API_KEY = '<api-key>';
    const ENDPOINT = '<endpoint>';
    const SPONSOR = '<sponsor>';
    const SPONSOR_KEY = '<sponsor-key>';
    const CLIENT_KEY = '<client-key>';

    public function createClient(array $config = array(), $serializerCacheDirectory = null, $debug = true)
    {
        return new VelmaClient(
            self::API_KEY,
            self::ENDPOINT,
            self::SPONSOR,
            self::SPONSOR_KEY,
            self::CLIENT_KEY,
            $config,
            $serializerCacheDirectory,
            $debug
        );
    }

    protected function getData($file)
    {
        return file_get_contents(__DIR__.'/data/'. $file . '.json');
    }

    protected function mockHandler()
    {
        return new MockHandler(func_get_args());
    }

    protected function mockResponse($statusCode, array $headers = array(), $body = null)
    {
        return new Response($statusCode, $headers, $body);
    }

    protected function mockRequestException($message, $statusCode, array $headers = array(), $body = null)
    {
        return new RequestException(
            $message,
            new Request('GET', 'test'),
            new Response($statusCode, $headers, $body)
        );
    }

    protected function mockClientException($message, $statusCode, array $headers = array(), $body = null)
    {
        return new ClientException(
            $message,
            new Request('GET', 'test'),
            new Response($statusCode, $headers, $body)
        );
    }
}
