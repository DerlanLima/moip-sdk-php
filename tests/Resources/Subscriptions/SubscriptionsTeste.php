<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use stdClass;
use Illuminate\Support\Collection;
use Softpampa\Moip\Moip;
use Softpampa\Moip\Tests\MoipTestCase;
use Softpampa\Moip\Subscriptions\Resources\Subscriptions;

class SubscriptionsTeste extends MoipTestCase {

    /**
     * @var Subscriptions  $subscription
     */
    private $subscription;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->subscription = $this->moip->subscriptions()->subscriptions();
        $this->client = $this->subscription->getClient();
    }

    /**
     * Get all subscriptions
     *
     * @see http://dev.moip.com.br/assinaturas-api/#listar-todas-assinaturas-get
     */
    public function testGetAllSubscriptions()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/subscriptions');

        $subscriptions = $this->subscription->all();

        $this->assertInstanceOf(Collection::class, $subscriptions);
        $this->assertInstanceOf(stdClass::class, $subscriptions->last());
        $this->assertEquals('assinatura21', $subscriptions->last()->code);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions', $this->client->getUrl());
    }

    /**
     * Find a subscription by code
     *
     * @see http://dev.moip.com.br/assinaturas-api/#consultar-detalhes-de-uma-assinatura-get
     */
    public function testFindASubscriptionByCode()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/subscription');

        $subscription = $this->subscription->find('assinatura21');

        $this->assertInstanceOf(Subscriptions::class, $subscription);
        $this->assertEquals('assinatura21', $subscription->code);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21', $this->client->getUrl());
    }

    /**
     * Create a new subscription
     *
     * @see http://dev.moip.com.br/assinaturas-api/#criar-assinaturas-post
     */
    public function testCreateANewSubscription()
    {
        $customers = $this->moip->subscriptions()->customers();
        $plans = $this->moip->subscriptions()->plans();

        // Mock response
        $customers->getClient()->addMockResponse('./tests/Mocks/customer');
        $plans->getClient()->addMockResponse('./tests/Mocks/plan');
        $this->client->addMockResponse(self::HTTP_CREATED);

        $customer = $customers->find('cliente02');
        $plan = $plans->find('plan101');

        $subscription = $this->subscription->setCode('assinatura21');
        $subscription->setPlan($plan)
                     ->setAmount(9990)
                     ->setCustomer($customer)
                     ->create();

        $this->assertInstanceOf(Subscriptions::class, $subscription);
        $this->assertEquals('assinatura21', $subscription->code);
        $this->assertEquals('POST', $this->client->getMethod());
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions?new_customer=false', $this->client->getUrl());
    }

    /**
     * Update a subscription
     *
     * @see http://dev.moip.com.br/assinaturas-api/#alterar-uma-assinatura-put
     */
    public function testUpdateASubscriptionByCode()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/subscription');
        $this->client->addMockResponse(self::HTTP_OK);

        $subscription = $this->subscription->find('assinatura21');
        $subscription->setNextInvoiceDate('2016-04-20');
        $subscription->save();

        $this->assertEquals(20, $subscription->next_invoice_date->day);
        $this->assertEquals(4, $subscription->next_invoice_date->month);
        $this->assertEquals(2016, $subscription->next_invoice_date->year);

        $this->assertInstanceOf(Subscriptions::class, $subscription);
        $this->assertEquals('assinatura21', $subscription->code);
        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21', $this->client->getUrl());
    }

    /**
     * Get all invoices from a subscription
     *
     * @see http://dev.moip.com.br/assinaturas-api/#listar-todas-as-faturas-de-uma-assinatura-get
     */
    public function testGetAllInvoicesFromSubscription()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/subscription');
        $this->client->addMockResponse('./tests/Mocks/invoices');

        $subscription = $this->subscription->find('assinatura21');
        $invoices = $subscription->invoices();

        $this->assertInstanceOf(Collection::class, $invoices);
        $this->assertInstanceOf(stdClass::class, $invoices->last());
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/invoices', $this->client->getUrl());
    }

    /**
     * Get all invoices with single request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#listar-todas-as-faturas-de-uma-assinatura-get
     */
    public function testGetAllInvoicesBySubscriptionCode()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/invoices');

        $subscription = $this->subscription->invoices('assinatura21');

        $this->assertInstanceOf(Collection::class, $subscription);
        $this->assertInstanceOf(stdClass::class, $subscription->last());
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/invoices', $this->client->getUrl());
    }

    /**
     * Suspend a subscription
     *
     * @see http://dev.moip.com.br/assinaturas-api/#suspender-reativar-e-cancelar-uma-assinatura-put
     */
    public function testSuspendASubscription()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/subscription');
        $this->client->addMockResponse(self::HTTP_OK);

        $subscription = $this->subscription->find('assinatura21');
        $subscription->suspend();

        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/suspend', $this->client->getUrl());
    }

    /**
     * Suspend a subscription with single request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#suspender-reativar-e-cancelar-uma-assinatura-put
     */
    public function testSuspendASubscriptionByCode()
    {
        // Mock response
        $this->client->addMockResponse(self::HTTP_OK);

        $subscription = $this->subscription->suspend('assinatura21');

        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/suspend', $this->client->getUrl());
    }

    /**
     * Cancel a subscription
     *
     * @see http://dev.moip.com.br/assinaturas-api/#cancelar-assinatura-put
     */
    public function testCanceldASubscription()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/subscription');
        $this->client->addMockResponse(self::HTTP_OK);

        $subscription = $this->subscription->find('assinatura21');
        $subscription->cancel();

        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/cancel', $this->client->getUrl());
    }

    /**
     * Cancel a subscription with single request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#cancelar-assinatura-put
     */
    public function testCanceldASubscriptionByCode()
    {
        // Mock response
        $this->client->addMockResponse(self::HTTP_OK);

        $subscription = $this->subscription->cancel('assinatura21');

        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/cancel', $this->client->getUrl());
    }

    /**
     * Activate a subscription
     *
     * @see http://dev.moip.com.br/assinaturas-api/#reativar-assinatura-put
     */
    public function testActivateASubscription()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/subscription');
        $this->client->addMockResponse(self::HTTP_OK);

        $subscription = $this->subscription->find('assinatura21');
        $subscription->activate();

        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/activate', $this->client->getUrl());
    }

    /**
     * Activate a subscription with single request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#reativar-assinatura-put
     */
    public function testActivateASubscriptionByCode()
    {
        // Mock response
        $this->client->addMockResponse(self::HTTP_OK);

        $subscription = $this->subscription->activate('assinatura21');

        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/activate', $this->client->getUrl());
    }

}
