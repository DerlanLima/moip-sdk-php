<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use Illuminate\Support\Collection;
use Softpampa\Moip\Tests\MoipTestCase;
use Softpampa\Moip\Subscriptions\Resources\Plans;

class PlansTest extends MoipTestCase {

    /**
     * @var  Plans  $plans  Moip Assinaturas Plans API
     */
    private $plans;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->plans = $this->moip->subscriptions()->plans();
    }

    /**
     * Gel all plans
     *
     * @see http://dev.moip.com.br/assinaturas-api/#listar-planos-get
     */
    public function testGetAllPlans()
    {
        // Mock response
        $this->addMockResponse(200, 'plans.json');

        $plans = $this->plans->all();

        $this->assertInstanceOf(Collection::class, $plans);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans', $this->getHttpUrl());
    }

    /**
     * Find a plan by code
     *
     * @see http://dev.moip.com.br/assinaturas-api/#consultar-plano-get
     */
    public function testFindAPlanByCode()
    {
        // Mock response
        $this->addMockResponse(200, 'plan.json');

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
    public function testFindANotFoundPlan()
    {
        // Mock response
        $this->addMockResponse(404);

        $plans = $this->plans->find('NOT_FOUND');

        $this->assertInstanceOf(Plans::class, $plans);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(404, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans/NOT_FOUND', $this->client->getUrl());
    }

    /**
     * Create a new plan
     *
     * @see http://dev.moip.com.br/assinaturas-api/#criar-plano-post
     */
    public function testCreateANewPlan()
    {
        // Mock response
        $this->addMockResponse(201, 'plan.json');

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
     *
     * @see http://dev.moip.com.br/assinaturas-api/#alterar-plano-put
     */
    public function testUpdateAPlan()
    {
        // Mock response
        $this->addMockResponse(200, 'plan.json');
        $this->addMockResponse(200);

        $plan = $this->plans->find('plan101');

        $plan->setAmount(500);
        $this->assertEquals(500, $plan->amount);
        $plan->save();

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans/plan101', $this->client->getUrl());
    }

    /**
     * Activate a plan
     *
     * @see http://dev.moip.com.br/assinaturas-api/#ativar-plano-put
     */
    public function testActivateAPlan()
    {
        // Mock response
        $this->addMockResponse(200, 'plan.json');
        $this->addMockResponse(200);

        $plan = $this->plans->find('plan101');
        $plan->activate();

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans/plan101/activate', $this->getHttpUrl());
    }

    /**
     * Activate a plan with single request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#desativar-plano-put
     */
    public function testActivateAPlanByCode()
    {
        // Mock response
        $this->addMockResponse(200);

        $plan = $this->plans->activate('plan101');

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans/plan101/activate', $this->getHttpUrl());
    }

    /**
     * Inactive a plan
     *
     * @see http://dev.moip.com.br/assinaturas-api/#desativar-plano-put
     */
    public function testInactivateAPlan()
    {
        // Mock response
        $this->addMockResponse(200, 'plan.json');
        $this->addMockResponse(200);

        $plan = $this->plans->find('plan101');
        $plan->inactivate();

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans/plan101/inactivate', $this->getHttpUrl());
    }

    /**
     * Inactivate a plan with single request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#desativar-plano-put
     */
    public function testInactivateAPlanByCode()
    {
        // Mock response
        $this->addMockResponse(200);

        $plan = $this->plans->inactivate('plan101');

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(MoipTestCase::SANDBOX . 'assinaturas/v1/plans/plan101/inactivate', $this->getHttpUrl());
    }

}
