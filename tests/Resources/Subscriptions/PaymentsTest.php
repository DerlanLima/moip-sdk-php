<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use GuzzleHttp\Stream\Stream;
use Softpampa\Moip\Subscriptions\Resources\Payments;
use Softpampa\Moip\Tests\MoipTestCase;

class PaymentsTest extends MoipTestCase {

    /**
     * @var  Payments  $payments  Moip Assinaturas Payment API
     */
    private $payments;

    /**
     * Setup test
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->payments = $this->moip->subscriptions()->payments();
    }

    /**
     * Find a payment by id
     */
    public function test_find_a_payment()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('payment.json'));

        $payment = $this->payments->find(6);

        $this->assertEquals(6, $payment->id);
        $this->assertInstanceOf(Payments::class, $payment);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/payments/6', $this->getHttpUrl());
    }

}
