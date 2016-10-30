<?php

namespace Softpampa\Moip\Tests;

use Softpampa\Moip\Moip;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

abstract class MoipTestCase extends TestCase {

    const HTTP_OK = 'HTTP/1.1 200 OK';

    const HTTP_NOT_FOUND = 'HTTP/1.1 404 Not Found';

    const HTTP_CREATED = 'HTTP/1.1 201 Created';

    const HTTP_NO_CONTENT = 'HTTP/1.1 204 No Content';

    /**
     * Moip
     *
     * @var \Softpampa\Moip\Moip
     */
    protected $moip;

    /**
     * Moip client
     *
     * @var \Softpampa\Moip\MoipClient
     */
    protected $client;

    /**
     * Set up all tests
     */
    public function setUp()
    {
        $this->moip = new Moip($this->mockMoipAuthentication(), Moip::SANDBOX);
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
