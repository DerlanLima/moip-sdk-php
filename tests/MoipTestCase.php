<?php

namespace Softpampa\Moip\Tests;

use GuzzleHttp\Stream\Stream;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Softpampa\Moip\Moip;
use Softpampa\Moip\MoipClient;
use Softpampa\Moip\MoipBasicAuth;
use Softpampa\Moip\Tests\Helpers\JsonFile;

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
     * @var  GuzzleHttp\Stream\Stream  $emptyBody
     */
    protected $emptyBody;

    /**
     * @var  string  $env  Moip environment
     */
    protected $env;

    /**
     * Set up all tests
     */
    public function setUp()
    {
        $this->moip = new Moip($this->mockMoipAuthentication(), Moip::SANDBOX);
        $this->client = $this->moip->getClient();
        $this->emptyBody = Stream::factory('{}');
    }

    public function getBodyMock($filename)
    {
        if (! $filename) {
            $body = $this->emptyBody;
        } else {
            $body = JsonFile::read($filename);
        }

        return Stream::factory($body);
    }

    public function addMockResponse($code, $mockFilename = null)
    {
        if ($this->env == 'MOCKBOX') {
            $this->client->addMockResponse($code, $this->getBodyMock($mockFilename));
        }
    }

    /**
     * Get HTTP Request Method
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->client->getHttpMethod();
    }

    /**
     * Get HTTP Response Status Code
     *
     * @return string
     */
    public function getHttpStatusCode()
    {
        return $this->client->getResponse()->getStatusCode();
    }

    /**
     * Get HTTP Request URL
     *
     * @return string
     */
    public function getHttpUrl()
    {
        return $this->client->getUrl();
    }

    /**
     * Handle with Moip authentication mock
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function mockMoipAuthentication()
    {
        $moipKey = getenv('MOIP_KEY');
        $moipToken = getenv('MOIP_TOKEN');

        if ($moipKey && $moipToken) {
            $this->env = 'SANDBOX';
            return new MoipBasicAuth($moipToken, $moipKey);
        }

        $auth = $this->getMockBuilder('Softpampa\Moip\MoipBasicAuth')
                ->setConstructorArgs(['MOIP_API_TOKEN', 'MOIP_API_KEY'])
                ->getMock();

        $auth->method('generateAuthorization')
                ->willReturn('Basic BASE64(MOIP_API_TOKEN:MOIP_API_KEY)');

        $this->env = 'MOCKBOX';

        return $auth;
    }

}
