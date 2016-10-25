<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

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
    }

    /**
     * Find a payment by id
     *
     * @see http://dev.moip.com.br/assinaturas-api/#consultar-detalhes-de-um-pagamento-de-assinatura-get
     */
    public function testFindAPaymentById()
    {
        // Mock response
        $this->addMockResponse(200, 'payment.json');

        $payment = $this->payments->find(6);

        $this->assertEquals(6, $payment->id);
        $this->assertInstanceOf(Payments::class, $payment);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/payments/6', $this->getHttpUrl());
    }

}
