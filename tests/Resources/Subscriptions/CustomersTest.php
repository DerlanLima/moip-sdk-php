<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use Illuminate\Support\Collection;
use Softpampa\Moip\Tests\MoipTestCase;
use Softpampa\Moip\Subscriptions\Resources\Customers;

class CustomersTest extends MoipTestCase {

    /**
     * @var  Customers  $customer
     */
    private $customer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->customer = $this->moip->subscriptions()->customers();
    }

    /**
     * Gel all customers
     * @see http://dev.moip.com.br/assinaturas-api/#listar-assinantes-get
     */
    public function testGetAllCustomers()
    {
        // Mock response
        $this->addMockResponse(200, 'customers.json');

        $customers = $this->customer->all();

        $this->assertInstanceOf(Collection::class, $customers);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/customers', $this->getHttpUrl());
    }

    /**
     * Find a customer by code
     * @see http://dev.moip.com.br/assinaturas-api/#consultar-assinante-get
     */
    public function testFindACustomerByCode()
    {
        // Mock response
        $this->addMockResponse(200, 'customer.json');

        $customers = $this->customer->find('cliente02');

        $this->assertInstanceOf(Customers::class, $customers);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/customers/cliente02', $this->getHttpUrl());
    }

    /**
     * Create a new customer
     * @see http://dev.moip.com.br/assinaturas-api/#criar-um-assinante-post
     */
    public function testCreateANewCustomer()
    {
        // Mock response
        $this->addMockResponse(201, 'customer.json');

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
     * Update a customer by code
     * @see http://dev.moip.com.br/assinaturas-api/#alterar-um-assinante-put
     */
    public function testUpdateACustomer()
    {
        // Mock response
        $this->addMockResponse(200, 'customer.json');
        $this->addMockResponse(200);

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
     * @see http://dev.moip.com.br/assinaturas-api/#atualizar-carto-do-assinante-put
     */
    public function testUpdateCustomerCreditCard()
    {
        // Mock response
        $this->addMockResponse(200, 'customer.json');
        $this->addMockResponse(200);

        $customer = $this->customer->find('cliente02');
        $customer->setBillingInfo('Nomedocliente', 5267691661858194, 4, 20);
        $customer->updateBillingInfo();

        $this->assertInstanceOf(Customers::class, $customer);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/customers/cliente02/billing_infos', $this->getHttpUrl());
    }

    /**
     * Update customer credit card with single request
     * @see http://dev.moip.com.br/assinaturas-api/#atualizar-carto-do-assinante-put
     */
    public function testUpdateCustomerCreditCardByCodeSettingBillingInfoAsDataArray()
    {
        // Mock response
        $this->addMockResponse(200);

        $customer = $this->customer->updateBillingInfo('cliente02', [
            'holder_name' => 'Novo nome',
            'number' => '5555666677778884',
            'expiration_month' => 04,
            'expiration_year' => 18
        ]);

        $this->assertInstanceOf(Customers::class, $customer);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/customers/cliente02/billing_infos', $this->getHttpUrl());
    }

}
