<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use Illuminate\Support\Collection;
use Softpampa\Moip\Exceptions\Client\ResourceNotFoundException;
use Softpampa\Moip\Moip;
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
        $this->client = $this->plans->getClient();
    }

    /**
     * Gel all plans
     *
     * @see http://dev.moip.com.br/assinaturas-api/#listar-planos-get
     */
    public function testGetAllPlans()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/plans');

        $plans = $this->plans->all();

        $this->assertInstanceOf(Collection::class, $plans);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/assinaturas/v1/plans', $this->client->getUrl());
    }

    /**
     * Find a plan by code
     *
     * @see http://dev.moip.com.br/assinaturas-api/#consultar-plano-get
     */
    public function testFindAPlanByCode()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/plan');

        $plan = $this->plans->find('plan101');

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/assinaturas/v1/plans/plan101', $this->client->getUrl());

        $this->assertEquals('plan101', $plan->code);
        $this->assertEquals(990, $plan->amount);
        $this->assertEquals(Plans::STATUS_ACTIVE, $plan->status);
        $this->assertEquals(Plans::PAYMENT_CREDIT_CARD, $plan->payment_method);
        $this->assertEquals('PlanoEspecial', $plan->name);
        $this->assertEquals('DescriçãodoPlanoEspecial', $plan->description);
        $this->assertEquals(true, $plan->trial->enabled);
        $this->assertEquals(30, $plan->trial->days);
        $this->assertEquals(true, $plan->trial->hold_setup_fee);
    }

    /**
     * Find a not found plan
     *
     * @expectedException \Softpampa\Moip\Exceptions\Client\ResourceNotFoundException
     */
    public function testFindANotFoundPlan()
    {
        // Mock response
        $this->client->addMockResponse(self::HTTP_NOT_FOUND);

        $this->plans->find('NOT_FOUND');
    }

    /**
     * Create a new plan
     *
     * @see http://dev.moip.com.br/assinaturas-api/#criar-plano-post
     */
    public function testCreateANewPlan()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/plan.created');

        $plan = $this->plans->setCode('plan101');
        $plan->setAmount(990)
             ->setName('PlanoEspecial')
             ->setDescription('DescriçãodoPlanoEspecial')
             ->setSetupFee(500)
             ->setMaxQdy(1)
             ->setInterval(Plans::INTERVAL_MONTH, 1)
             ->setTrial(true, 30, true)
             ->setPaymentMethod()
             ->create();

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('POST', $this->client->getMethod());
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/assinaturas/v1/plans', $this->client->getUrl());
    }

    /**
     * Update a exist plan
     *
     * @see http://dev.moip.com.br/assinaturas-api/#alterar-plano-put
     */
    public function testUpdateAPlan()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/plan');
        $this->client->addMockResponse(self::HTTP_OK);

        $plan = $this->plans->find('plan101');

        $plan->setAmount(500);
        $this->assertEquals(500, $plan->amount);
        $plan->save();

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/assinaturas/v1/plans/plan101', $this->client->getUrl());
    }

    /**
     * Activate a plan
     *
     * @see http://dev.moip.com.br/assinaturas-api/#ativar-plano-put
     */
    public function testActivateAPlan()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/plan');
        $this->client->addMockResponse(self::HTTP_OK);

        $plan = $this->plans->find('plan101');
        $plan->activate();

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/assinaturas/v1/plans/plan101/activate', $this->client->getUrl());
    }

    /**
     * Activate a plan with single request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#desativar-plano-put
     */
    public function testActivateAPlanByCode()
    {
        // Mock response
        $this->client->addMockResponse(self::HTTP_OK);

        $plan = $this->plans->activate('plan101');

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/assinaturas/v1/plans/plan101/activate', $this->client->getUrl());
    }

    /**
     * Inactive a plan
     *
     * @see http://dev.moip.com.br/assinaturas-api/#desativar-plano-put
     */
    public function testInactivateAPlan()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/plan');
        $this->client->addMockResponse(self::HTTP_OK);

        $plan = $this->plans->find('plan101');
        $plan->inactivate();

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/assinaturas/v1/plans/plan101/inactivate', $this->client->getUrl());
    }

    /**
     * Inactivate a plan with single request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#desativar-plano-put
     */
    public function testInactivateAPlanByCode()
    {
        // Mock response
        $this->client->addMockResponse(self::HTTP_OK);

        $plan = $this->plans->inactivate('plan101');

        $this->assertInstanceOf(Plans::class, $plan);
        $this->assertEquals('PUT', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/assinaturas/v1/plans/plan101/inactivate', $this->client->getUrl());
    }

}
