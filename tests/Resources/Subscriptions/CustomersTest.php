<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use GuzzleHttp\Stream\Stream;
use Illuminate\Support\Collection;
use Softpampa\Moip\Subscriptions\Resources\Customers;
use Softpampa\Moip\Tests\MoipTestCase;

class CustomersTest extends MoipTestCase {

    private $customer;

    /**
     * Setup test
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->customer = $this->moip->subscriptions()->customers();
    }

    /**
     * Gel all customers
     */
    public function test_get_all_customers()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('customers.json'));

        $customers = $this->customer->all();

        $this->assertInstanceOf(Collection::class, $customers);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/customers', $this->getHttpUrl());
    }

    /**
     * Find a customer
     */
    public function test_find_a_customer()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('customer.json'));

        $customers = $this->customer->find('cliente02');

        $this->assertInstanceOf(Customers::class, $customers);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/customers/cliente02', $this->getHttpUrl());
    }

    /**
     * Create a customer
     */
    public function test_create_a_customer()
    {
        // Mock response
        $this->client->addMockResponse(201, $this->getBodyMock('customer.json'));

        $customer = $this->customer->setCode('cliente2')
                ->setFullname('ClienteSobrenome')
                ->setCpf(12345679891)
                ->setBirthdate('1980-04-26')
                ->setEmail('nome2@exemplo.com.br')
                ->setPhone(26, 934343434)
                ->setAddress('RuaNomedaRua2', 1002, 'Casa2', 'NomedoBairro2', 'SãoPaulo', 'SP', 05015010)
                ->setBillingInfo('Nomedocliente', 5267691661858194, 4, 20)
                ->create();

        $this->assertInstanceOf(Customers::class, $customer);

        $this->assertEquals('cliente2', $customer->code);
        $this->assertEquals('ClienteSobrenome', $customer->fullname);
        $this->assertEquals(26, $customer->birthdate_day);
        $this->assertEquals(4, $customer->birthdate_month);
        $this->assertEquals(1980, $customer->birthdate_year);
        $this->assertEquals('RuaNomedaRua2', $customer->address->street);
        $this->assertEquals(1002, $customer->address->number);
        $this->assertEquals('Casa2', $customer->address->complement);
        $this->assertEquals('NomedoBairro2', $customer->address->district);
        $this->assertEquals('SãoPaulo', $customer->address->city);
        $this->assertEquals('SP', $customer->address->state);
        $this->assertEquals(5267691661858194, $customer->billing_info->credit_card->number);
        $this->assertEquals('Nomedocliente', $customer->billing_info->credit_card->holder_name);
        $this->assertEquals(4, $customer->billing_info->credit_card->expiration_month);
        $this->assertEquals(20, $customer->billing_info->credit_card->expiration_year);

        $this->assertEquals('POST', $this->getHttpMethod());
        $this->assertEquals(201, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/customers', $this->getHttpUrl());
    }

    /**
     * Update a customer by id
     */
    public function test_update_a_customer()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('customer.json'));
        $this->client->addMockResponse(200);

        $customer = $this->customer->find('cliente02');
        $customer->setEmail('outromail@exemplo.com.br');
        $customer->save();

        $this->assertInstanceOf(Customers::class, $customer);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/customers/cliente02', $this->getHttpUrl());
    }

    /**
     * Update a customer credit card
     */
    public function test_update_customer_creadit_card()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('customer.json'));
        $this->client->addMockResponse(200);

        $customer = $this->customer->find('cliente02');
        $customer->setBillingInfo('Nomedocliente', 5267691661858194, 4, 20);
        $customer->updateBillingInfo();

        $this->assertInstanceOf(Customers::class, $customer);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/customers/cliente02/billing_infos', $this->getHttpUrl());
    }

}
