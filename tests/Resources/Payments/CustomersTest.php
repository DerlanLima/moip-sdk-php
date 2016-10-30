<?php

namespace Softpampa\Moip\Tests\Resources\Payments;

use Softpampa\Moip\Moip;
use Softpampa\Moip\Payments\Resources\Customers;
use Softpampa\Moip\Tests\MoipTestCase;

class CustomersTest extends MoipTestCase {

    /**
     * @var \Softpampa\Moip\Payments\Resources\Customers
     */
    protected $customer;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->customer = $this->moip->payments()->customers();
        $this->client = $this->customer->getClient();
    }

    /**
     * Create a new customer
     */
    public function testCreateANewCustomer()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/client.created');

        $customer = $this->customer;

        $customer->setFullname('João da Silva Cunha')
                 ->setBirthdate('1990-05-03')
                 ->setEmail('joaodasilvacunha@mail.com')
                 ->setOwnId(uniqid())
                 ->setPhone(51, 97084521)
                 ->setTaxDocument('20411375091')
                 ->addAddress($customer::ADDRESS_BILLING, 'Rua Bento Gonçalves', 21, '', 'Jardim do Cedro', 'Lajeado', 'RS', 95900000)
                 ->create();

        $this->assertInstanceOf(Customers::class, $customer);
        $this->assertEquals('POST', $this->client->getMethod());
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/v2/customers', $this->client->getUrl());
    }

    /**
     * Find a customer by id
     */
    public function testFindACustomerById()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/client');

        $customer = $this->customer->find('CUS-Y6L4AGQN8HKQ');

        $this->assertInstanceOf(Customers::class, $customer);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/v2/customers/CUS-Y6L4AGQN8HKQ', $this->client->getUrl());
    }

    /**
     * Add customer credit card
     */
    public function addCustomerCreditCard()
    {
        $customer = $this->customer->find('CUS-Y6L4AGQN8HKQ');
        $customer->addNewCreditCard(4, 20, '5555666677778884', '123', $customer)
                 ->save();
    }

}
