<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use GuzzleHttp\Stream\Stream;
use Illuminate\Support\Collection;
use Softpampa\Moip\Tests\MoipTestCase;
use Softpampa\Moip\Subscriptions\Resources\Invoices;

class InvoicesTest extends MoipTestCase {

    /**
     * @var  Invoices  $invoices  Moip Assinaturas Invoices API
     */
    private $invoices;

    /**
     * Setup test
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->invoices = $this->moip->subscriptions()->invoices();
    }

    /**
     * Find a invoice
     */
    public function test_find_a_invoice()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('invoice.json'));

        $invoice = $this->invoices->find('1729934');

        $this->assertEquals('1729934', $invoice->id);
        $this->assertInstanceOf(Invoices::class, $invoice);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/invoices/1729934', $this->getHttpUrl());
    }

    /**
     * Get all payments
     */
    public function test_get_all_payments()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('invoice.json'));
        $this->client->addMockResponse(200, $this->getBodyMock('payments.json'));

        $payments = $this->invoices->find('1729934')->payments();

        $this->assertInstanceOf(Collection::class, $payments);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/invoices/1729934/payments', $this->getHttpUrl());
    }

}
