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
    }

    /**
     * Get all subscriptions
     *
     * @see http://dev.moip.com.br/assinaturas-api/#listar-todas-assinaturas-get
     */
    public function testGetAllSubscriptions()
    {
        // Mock response
        $this->addMockResponse(200, 'subscriptions.json');

        $subscriptions = $this->subscription->all();

        $this->assertInstanceOf(Collection::class, $subscriptions);
        $this->assertInstanceOf(stdClass::class, $subscriptions->last());
        $this->assertEquals('assinatura21', $subscriptions->last()->code);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/subscriptions', $this->getHttpUrl());
    }

    /**
     * Find a subscription by code
     *
     * @see http://dev.moip.com.br/assinaturas-api/#consultar-detalhes-de-uma-assinatura-get
     */
    public function testFindASubscriptionByCode()
    {
        // Mock response
        $this->addMockResponse(200, 'subscription.json');

        $subscription = $this->subscription->find('assinatura21');

        $this->assertInstanceOf(Subscriptions::class, $subscription);
        $this->assertEquals('assinatura21', $subscription->code);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/subscriptions/assinatura21', $this->getHttpUrl());
    }

    /**
     * Create a new subscription
     *
     * @see http://dev.moip.com.br/assinaturas-api/#criar-assinaturas-post
     */
    public function testCreateANewSubscription()
    {
        // Mock response
        $this->addMockResponse(200, 'plan.json');
        $this->addMockResponse(200, 'customer.json');
        $this->addMockResponse(201);

        $customer = $this->moip->subscriptions()->customers()->find('cliente02');
        $plan = $this->moip->subscriptions()->plans()->find('plan101');

        $subscription = $this->moip->subscriptions()->subscriptions();
        $subscription->setCode('assinatura21')
                     ->setPlan($plan)
                     ->setAmount(9990)
                     ->setCustomer($customer)
                     ->create();

        $this->assertInstanceOf(Subscriptions::class, $subscription);
        $this->assertEquals('assinatura21', $subscription->code);
        $this->assertEquals('POST', $this->getHttpMethod());
        $this->assertEquals(201, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/subscriptions?new_customer=false', $this->getHttpUrl());
    }

    /**
     * Update a subscription
     *
     * @see http://dev.moip.com.br/assinaturas-api/#alterar-uma-assinatura-put
     */
    public function testUpdateASubscriptionByCode()
    {
        // Mock response
        $this->addMockResponse(200, 'subscription.json');
        $this->addMockResponse(200);

        $subscription = $this->subscription->find('assinatura21');
        $subscription->setNextInvoiceDate('2016-04-20');
        $subscription->save();

        $this->assertEquals(20, $subscription->next_invoice_date->day);
        $this->assertEquals(4, $subscription->next_invoice_date->month);
        $this->assertEquals(2016, $subscription->next_invoice_date->year);

        $this->assertInstanceOf(Subscriptions::class, $subscription);
        $this->assertEquals('assinatura21', $subscription->code);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/subscriptions/assinatura21', $this->getHttpUrl());
    }

    /**
     * Get all invoices from a subscription
     *
     * @see http://dev.moip.com.br/assinaturas-api/#listar-todas-as-faturas-de-uma-assinatura-get
     */
    public function testGetAllInvoicesFromSubscription()
    {
        // Mock response
        $this->addMockResponse(200, 'subscription.json');
        $this->addMockResponse(200, 'invoices.json');

        $subscription = $this->subscription->find('assinatura21');
        $invoices = $subscription->invoices();

        $this->assertInstanceOf(Collection::class, $invoices);
        $this->assertInstanceOf(stdClass::class, $invoices->last());
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/subscriptions/assinatura21/invoices', $this->getHttpUrl());
    }

    /**
     * Get all invoices with single request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#listar-todas-as-faturas-de-uma-assinatura-get
     */
    public function testGetAllInvoicesBySubscriptionCode()
    {
        // Mock response
        $this->addMockResponse(200, 'invoices.json');

        $subscription = $this->subscription->invoices('assinatura21');

        $this->assertInstanceOf(Collection::class, $subscription);
        $this->assertInstanceOf(stdClass::class, $subscription->last());
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/subscriptions/assinatura21/invoices', $this->getHttpUrl());
    }

    /**
     * Suspend a subscription
     *
     * @see http://dev.moip.com.br/assinaturas-api/#suspender-reativar-e-cancelar-uma-assinatura-put
     */
    public function testSuspendASubscription()
    {
        // Mock response
        $this->addMockResponse(200, 'subscription.json');
        $this->addMockResponse(200);

        $subscription = $this->subscription->find('assinatura21');
        $subscription->suspend();

        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/suspend', $this->getHttpUrl());
    }

    /**
     * Suspend a subscription with single request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#suspender-reativar-e-cancelar-uma-assinatura-put
     */
    public function testSuspendASubscriptionByCode()
    {
        // Mock response
        $this->addMockResponse(200);

        $subscription = $this->subscription->suspend('assinatura21');

        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/suspend', $this->getHttpUrl());
    }

    /**
     * Cancel a subscription
     *
     * @see http://dev.moip.com.br/assinaturas-api/#cancelar-assinatura-put
     */
    public function testCanceldASubscription()
    {
        // Mock response
        $this->addMockResponse(200, 'subscription.json');
        $this->addMockResponse(200);

        $subscription = $this->subscription->find('assinatura21');
        $subscription->cancel();

        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/cancel', $this->getHttpUrl());
    }

    /**
     * Cancel a subscription with single request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#cancelar-assinatura-put
     */
    public function testCanceldASubscriptionByCode()
    {
        // Mock response
        $this->addMockResponse(200);

        $subscription = $this->subscription->cancel('assinatura21');

        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/cancel', $this->getHttpUrl());
    }

    /**
     * Activate a subscription
     *
     * @see http://dev.moip.com.br/assinaturas-api/#reativar-assinatura-put
     */
    public function testActivateASubscription()
    {
        // Mock response
        $this->addMockResponse(200, 'subscription.json');
        $this->addMockResponse(200);

        $subscription = $this->subscription->find('assinatura21');
        $subscription->activate();

        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/activate', $this->getHttpUrl());
    }

    /**
     * Activate a subscription with single request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#reativar-assinatura-put
     */
    public function testActivateASubscriptionByCode()
    {
        // Mock response
        $this->addMockResponse(200);

        $subscription = $this->subscription->activate('assinatura21');

        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/assinaturas/v1/subscriptions/assinatura21/activate', $this->getHttpUrl());
    }

}
