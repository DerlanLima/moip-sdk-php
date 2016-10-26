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
    }

    /**
     * Find a invoice by id
     *
     * @see http://dev.moip.com.br/assinaturas-api/#consultar-detalhes-de-uma-fatura-get
     */
    public function testFindAInvoiceById()
    {
        // Mock response
        $this->addMockResponse(200, 'invoice.json');

        $invoice = $this->invoices->find('1729934');

        $this->assertEquals('1729934', $invoice->id);
        $this->assertInstanceOf(Invoices::class, $invoice);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/invoices/1729934', $this->getHttpUrl());
    }

    /**
     * Get all payments from invoice
     *
     * @see http://dev.moip.com.br/assinaturas-api/#listar-todos-os-pagamentos-de-uma-fatura-get
     */
    public function testGetAllPaymentsFromInvoice()
    {
        // Mock response
        $this->addMockResponse(200, 'invoice.json');
        $this->addMockResponse(200, 'payments.json');

        $payments = $this->invoices->find('1729934')->payments();

        $this->assertInstanceOf(Collection::class, $payments);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/invoices/1729934/payments', $this->getHttpUrl());
    }

}
