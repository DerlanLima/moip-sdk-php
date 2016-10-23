<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use stdClass;
use Illuminate\Support\Collection;
use Softpampa\Moip\Tests\MoipTestCase;
use Softpampa\Moip\Subscriptions\Resources\Subscriptions;

class SubscriptionsTeste extends MoipTestCase {

    /**
     * @var Subscriptions  $subscription
     */
    private $subscription;

    /**
     * Set up test
     */
    public function setUp()
    {
        parent::setUp();

        $this->subscription = $this->moip->subscriptions()->subscriptions();
    }

    /**
     * Get all subscriptions
     */
    public function test_get_all_subscriptions()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('subscriptions.json'));

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
     */
    public function test_find_a_subscription_by_code()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('subscription.json'));

        $subscription = $this->subscription->find('assinatura21');

        $this->assertInstanceOf(Subscriptions::class, $subscription);
        $this->assertEquals('assinatura21', $subscription->code);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/subscriptions/assinatura21', $this->getHttpUrl());
    }

    /**
     * Update a subscription
     */
    public function test_update_a_subscription()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('subscription.json'));
        $this->client->addMockResponse(200);

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

}
