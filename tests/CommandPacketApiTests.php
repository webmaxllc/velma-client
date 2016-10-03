<?php

use NRM\SimplyRetsClient\Model\Listing;
use NRM\SimplyRetsClient\PropertyParameterSet;

/**
 * @author Frank Bardon Jr. <frankbardon@gmail.com>
 */
class CommandPacketApiTests extends ClientTestCase
{
    public function testSuccessResponse()
    {
        $client = $this->createClient($this->getHandledConfig('success'));
        $cp = $client->createCommandPacket();

        $this->assertInstanceOf('Webmax\VelmaClient\Model\VelmaResponse', $client->sendCommandPacket($cp));
    }

    public function testSuccessResponseIsObject()
    {
        $client = $this->createClient($this->getHandledConfig('success'));
        $cp = $client->createCommandPacket();
        $response = $client->sendCommandPacket($cp);

        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isFailure());
        $this->assertSame('100.0.0', $response->getCode());
        $this->assertSame('success', $response->getStatus());
        $this->assertInternalType('string', $response->getMessage());
        $this->assertInternalType('string', $response->getJobId());
    }

    private function getHandledConfig($which)
    {
        switch ($which) {
            case 'success':
                $response = $this->mockResponse(200, array(), $this->getData('success'));
                break;
            default:
                throw new \InvalidArgumentException('Unable to create listing handler configuration');
        }

        return array(
            'handler' => $this->mockHandler($response)
        );
    }
}
