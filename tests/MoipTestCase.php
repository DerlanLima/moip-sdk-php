<?php

namespace Softpampa\Moip\Tests;

use GuzzleHttp\Subscriber\Mock;
use PHPUnit\Framework\TestCase;
use Softpampa\Moip\Moip;
use Softpampa\Moip\MoipClient;

abstract class MoipTestCase extends TestCase {

    /**
     * @var  Moip  $moip
     */
    protected $moip;

    /**
     * @var  MoipClient  $client
     */
    protected $client;

    /**
     * @var  GuzzleHttp\Client  $httpClient
     */
    protected $httpClient;

    /**
     * @var  Symfony\Component\EventDispatcher\EventDispatcher  $eventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Set up all tests
     */
    public function setUp()
    {
        $this->moip = new Moip($this->mockMoipAuthentication(), Moip::SANDBOX);

        $this->client = $this->moip->getClient();
        $this->httpClient = $this->client->getHttpClient();
        $this->eventDispatcher = $this->moip->getEvent()->getDispatcher();
    }

    /**
     * Attack Mock subscriber on GuzzleHttp\Client
     *
     * @param  GuzzleHttp\Subscriber\Mock  $mock
     * @return void
     */
    public function attackMockSubscriber(Mock $mock)
    {
        $this->httpClient->getEmitter()->attach($mock);
    }

    /**
     * Handle with Moip authentication mock
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function mockMoipAuthentication()
    {
        $auth = $this->getMockBuilder('Softpampa\Moip\MoipBasicAuth')
                ->setConstructorArgs(['MOIP_API_TOKEN', 'MOIP_API_KEY'])
                ->getMock();

        $auth->method('generateAuthorization')
                ->willReturn('Basic BASE64(MOIP_API_TOKEN:MOIP_API_KEY)');

        return $auth;
    }

}
