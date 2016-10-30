<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use Illuminate\Support\Collection;
use Softpampa\Moip\Moip;
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
        $this->client = $this->customer->getClient();
    }

    /**
     * Gel all customers
     * @see http://dev.moip.com.br/assinaturas-api/#listar-assinantes-get
     */
    public function testGetAllCustomers()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/customers');

        $customers = $this->customer->all();

        $this->assertInstanceOf(Collection::class, $customers);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/customers', $this->client->getUrl());
    }

    /**
     * Find a customer by code
     * @see http://dev.moip.com.br/assinaturas-api/#consultar-assinante-get
     */
    public function testFindACustomerByCode()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/customer');

        $customers = $this->customer->find('cliente02');

        $this->assertInstanceOf(Customers::class, $customers);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/customers/cliente02', $this->client->getUrl());
    }

    /**
     * Find a not found customer
     *
     * @see http://dev.moip.com.br/assinaturas-api/#criar-um-assinante-post
     * @expectedException \Softpampa\Moip\Exceptions\Client\ResourceNotFoundException
     */
    public function testFindANotFoundCustomerByCode()
    {

        // Mock response
        $this->client->addMockResponse(self::HTTP_NOT_FOUND);

        $this->customer->find('NUMIXISTI');
    }

    /**
     * Create a new customer
     *
     * @see http://dev.moip.com.br/assinaturas-api/#criar-um-assinante-post
     */
    public function testCreateANewCustomer()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/customer.created');

        $customer = $this->customer->setCode('cliente2');
        $customer->setFullname('ClienteSobrenome')
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

        $this->assertEquals('POST', $this->client->getMethod());
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/customers', $this->client->getUrl());
    }

    /**
     * Update a customer by code
     *
     * @see http://dev.moip.com.br/assinaturas-api/#alterar-um-assinante-put
     */
    public function testUpdateACustomer()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/customer');
        $this->client->addMockResponse(self::HTTP_OK);

        $customer = $this->customer->find('cliente02');
        $customer->setEmail('outromail@exemplo.com.br');
        $customer->save();

        $this->assertInstanceOf(Customers::class, $customer);
        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/customers/cliente02', $this->client->getUrl());
    }

    /**
     * Update a customer credit card
     *
     * @see http://dev.moip.com.br/assinaturas-api/#atualizar-carto-do-assinante-put
     */
    public function testUpdateCustomerCreditCard()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/customer');
        $this->client->addMockResponse(self::HTTP_OK);

        $customer = $this->customer->find('cliente02');
        $customer->setBillingInfo('Nomedocliente', 5267691661858194, 4, 20);
        $customer->updateBillingInfo();

        $this->assertInstanceOf(Customers::class, $customer);
        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/customers/cliente02/billing_infos', $this->client->getUrl());
    }

    /**
     * Update customer credit card with single request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#atualizar-carto-do-assinante-put
     */
    public function testUpdateCustomerCreditCardByCodeSettingBillingInfoAsDataArray()
    {
        // Mock response
        $this->client->addMockResponse(self::HTTP_OK);

        $customer = $this->customer->updateBillingInfo('cliente02', [
            'holder_name' => 'Novo nome',
            'number' => '5555666677778884',
            'expiration_month' => 04,
            'expiration_year' => 18
        ]);

        $this->assertInstanceOf(Customers::class, $customer);
        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/customers/cliente02/billing_infos', $this->client->getUrl());
    }

}
