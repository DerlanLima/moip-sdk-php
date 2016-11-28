<?php

namespace Softpampa\Moip\Tests\Resources\Payments;

use Illuminate\Support\Collection;
use Softpampa\Moip\Helpers\CreditCard;
use Softpampa\Moip\Moip;
use Softpampa\Moip\Payments\Resources\Orders;
use Softpampa\Moip\Payments\Resources\Payments;
use Softpampa\Moip\Tests\MoipTestCase;

class OrdersTest extends MoipTestCase {

    /**
     * Orders API
     *
     * @var \Softpampa\Moip\Payments\Resources\Orders
     */
    protected $orders;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->orders = $this->moip->payments()->orders();
        $this->client = $this->orders->getClient();
    }

    public function testCreateANewOrderForCustomer()
    {
        // Mock response
        $customer = $this->moip->payments()->customers();
        $customer->getClient()->addMockResponse('./tests/Mocks/client');
        $this->client->addMockResponse('./tests/Mocks/order.created');

        $customer = $customer->find('CUS-Y6L4AGQN8HKQ');

        $order = $this->orders->setOwnId(uniqid());
        $order->setCustomer($customer)
              ->addItem('Descrição do pedido', 1, 'Mais info...', 89.90)
              ->setShippingAmount(27.5)
              ->create();

        $this->assertInstanceOf(Orders::class, $order);
        $this->assertEquals(11640, $order->amount->total);
        $this->assertEquals('POST', $this->client->getMethod());
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/v2/orders', $this->client->getUrl());
    }

    /**
     * Find a order by id
     */
    public function testFindAOrderById()
    {
        $this->client->addMockResponse('./tests/Mocks/order');

        $order = $this->orders->find('ORD-4HY0KOA9Q73F');

        $this->assertInstanceOf(Orders::class, $order);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/v2/orders/ORD-4HY0KOA9Q73F', $this->client->getUrl());
    }

    /**
     * Get all orders
     */
    public function testGetAllOrders()
    {
        $this->client->addMockResponse('./tests/Mocks/orders');

        $orders = $this->orders->all();

        $this->assertInstanceOf(Collection::class, $orders);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/v2/orders', $this->client->getUrl());
    }

    /**
     * Pay a order
     */
//    public function testPayAOrder()
//    {
//        $customer = $this->moip->payments()->customers();
//        $payment = $this->moip->payments()->payments();
//
//        // Mock reponse
//        $this->client->addMockResponse('./tests/Mocks/order');
//        $customer->getClient()->addMockResponse('./tests/Mocks/client');
//        $payment->getClient()->addMockResponse('./tests/Mocks/_payment.created');
//
//        $customer = $customer->find('CUS-Y6L4AGQN8HKQ');
//        $order = $this->orders->find('ORD-4HY0KOA9Q73F');
//
//        $order->payments()->setCreditCard(new CreditCard(4, 20, '4916979384868980', 825, $customer))->execute();
//
//        $this->assertEquals('POST', $this->client->getMethod());
//        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
//        $this->assertEquals('https://sandbox.moip.com.br/v2/orders/ORD-4HY0KOA9Q73F/payments', $this->client->getUrl());
//
//    }

}
