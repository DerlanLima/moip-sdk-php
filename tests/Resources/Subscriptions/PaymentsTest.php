<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use Softpampa\Moip\Moip;
use Softpampa\Moip\Tests\MoipTestCase;
use Softpampa\Moip\Subscriptions\Resources\Payments;

class PaymentsTest extends MoipTestCase {

    /**
     * @var  Payments  $payments  Moip Assinaturas Payment API
     */
    private $payments;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->payments = $this->moip->subscriptions()->payments();
        $this->client = $this->payments->getClient();
    }

    /**
     * Find a payment by id
     *
     * @see http://dev.moip.com.br/assinaturas-api/#consultar-detalhes-de-um-pagamento-de-assinatura-get
     */
    public function testFindAPaymentById()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/payment');

        $payment = $this->payments->find(6);

        $this->assertEquals(6, $payment->id);
        $this->assertInstanceOf(Payments::class, $payment);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/payments/6', $this->client->getUrl());
    }

}
