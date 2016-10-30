<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use Illuminate\Support\Collection;
use Softpampa\Moip\Moip;
use Softpampa\Moip\Tests\MoipTestCase;
use Softpampa\Moip\Subscriptions\Resources\Invoices;

class InvoicesTest extends MoipTestCase {

    /**
     * @var  Invoices  $invoices  Moip Assinaturas Invoices API
     */
    private $invoices;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->invoices = $this->moip->subscriptions()->invoices();
        $this->client = $this->invoices->getClient();
    }

    /**
     * Find a invoice by id
     *
     * @see http://dev.moip.com.br/assinaturas-api/#consultar-detalhes-de-uma-fatura-get
     */
    public function testFindAInvoiceById()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/invoice');

        $invoice = $this->invoices->find('1729934');

        $this->assertEquals('1729934', $invoice->id);
        $this->assertInstanceOf(Invoices::class, $invoice);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/invoices/1729934', $this->client->getUrl());
    }

    /**
     * Get all payments from invoice
     *
     * @see http://dev.moip.com.br/assinaturas-api/#listar-todos-os-pagamentos-de-uma-fatura-get
     */
    public function testGetAllPaymentsFromInvoice()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/invoice');
        $this->client->addMockResponse('./tests/Mocks/payments');

        $payments = $this->invoices->find('1729934')->payments();

        $this->assertInstanceOf(Collection::class, $payments);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/invoices/1729934/payments', $this->client->getUrl());
    }

}
