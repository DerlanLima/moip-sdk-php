<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use GuzzleHttp\Stream\Stream;
use Illuminate\Support\Collection;
use Softpampa\Moip\Subscriptions\Resources\Plans;
use Softpampa\Moip\Tests\MoipTestCase;

class PlansTest extends MoipTestCase {

    /**
     * @var  Plans  $plans  Moip Assinaturas Plans API
     */
    private $plans;

    /**
     * Setup test
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->plans = $this->moip->subscriptions()->plans();
    }

    /**
     * Gel all plans
     */
    public function test_get_all_plans()
    {
        $this->client->addMockResponse(200, $this->getBodyMock('plans.json'));

        $plans = $this->plans->all();

        $this->assertInstanceOf(Collection::class, $plans);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans', $this->getHttpUrl());
    }

    /**
     * Find a plan
     */
    public function test_find_a_plan()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('plan.json'));

        $plan = $this->plans->find('plan101');

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans/plan101', $this->getHttpUrl());

        $this->assertEquals('plan101', $plan->code);
        $this->assertEquals(990, $plan->amount);
        $this->assertEquals(Plans::STATUS_ACTIVE, $plan->status);
        $this->assertEquals(Plans::PAYMNET_CREDIT_CARD, $plan->payment_method);
        $this->assertEquals('PlanoEspecial', $plan->name);
        $this->assertEquals('DescriçãodoPlanoEspecial', $plan->description);
        $this->assertEquals(true, $plan->trial->enabled);
        $this->assertEquals(30, $plan->trial->days);
        $this->assertEquals(true, $plan->trial->hold_setup_fee);
    }

    /**
     * Find a not found plan
     */
    public function test_find_a_not_found_plan()
    {
        // Mock response
        $this->client->addMockResponse(404, $this->emptyBody);

        $plans = $this->plans->find('NOT_FOUND');

        $this->assertInstanceOf(Plans::class, $plans);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(404, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans/NOT_FOUND', $this->client->getUrl());
    }

    /**
     * Create a plan
     */
    public function test_create_a_plan()
    {
        // Mock response
        $this->client->addMockResponse(201, $this->getBodyMock('plan.json'));

        $plans = $this->plans->setCode('plan101')
                ->setAmount(990)
                ->setName('PlanoEspecial')
                ->setDescription('DescriçãodoPlanoEspecial')
                ->setSetupFee(500)
                ->setMaxQdy(1)
                ->setInterval(Plans::INTERVAL_MONTH, 1)
                ->setTrial(true, 30, true)
                ->setPaymentMethod()
                ->create();

        $this->assertInstanceOf(Plans::class, $plans);
        $this->assertEquals('POST', $this->getHttpMethod());
        $this->assertEquals(201, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans', $this->client->getUrl());
    }

    /**
     * Update a exist plan
     */
    public function test_update_a_plan()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('plan.json'));
        $this->client->addMockResponse(201);

        $plan = $this->plans->find('plan101');

        $plan->setAmount(500);
        $this->assertEquals(500, $plan->amount);
        $plan->save();

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(201, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans/plan101', $this->client->getUrl());
    }

    /**
     * Activate a plan
     */
    public function test_activate_a_plan()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('plan.json'));
        $this->client->addMockResponse(201);

        $plan = $this->plans->find('plan101');
        $plan->activate();

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(201, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans/plan101/activate', $this->getHttpUrl());
    }

    /**
     * Inactive a plan
     */
    public function test_inactivate_a_plan()
    {
        // Mock response
        $this->client->addMockResponse(200, $this->getBodyMock('plan.json'));
        $this->client->addMockResponse(201);

        $plan = $this->plans->find('plan101');
        $plan->inactivate();

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(201, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans/plan101/inactivate', $this->getHttpUrl());
    }

    /**
     * Inactivate a plan with one request
     */
    public function test_inactivate_a_plan_with_one_request()
    {
        // Mock response
        $this->client->addMockResponse(201);

        $plan = $this->plans->inactivate('plan101');

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(201, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans/plan101/inactivate', $this->getHttpUrl());
    }

}
