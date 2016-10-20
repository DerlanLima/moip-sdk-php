<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use Softpampa\Moip\Tests\MoipTestCase;

class PlansTest extends MoipTestCase {

    /**
     * @var  Plans  $plans  Moip Assinaturas Plans API
     */
    private $plans;

    /**
     * @var  string  $mockedResponse
     */
    private $mockedResponse = '{"amount":990,"max_qty":1,"setup_fee":500,"interval":{"unit":"MONTH","length":1},"status":"ACTIVE","description":"DescriçãodoPlanoEspecial","name":"PlanoEspecial","billing_cycles":12,"code":"plan101","trial":{"enabled":true,"days":30,"hold_setup_fee":true},"payment_method":"CREDIT_CARD"}';

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
     * @test
     */
    public function get_all_plans()
    {
        $body = Stream::factory('{"plans": ' . $this->mockedResponse . '}');
        $this->client->addMockResponse(200, $body);

        $plans = $this->plans->all();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $plans);
        $this->assertEquals('GET', $this->client->getHttpMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/assinaturas/v1/plans', $this->client->getUrl());
    }

    /**
     * @test
     */
    public function find_a_not_found_plan()
    {
        $body = Stream::factory('{}');
        $this->client->addMockResponse(404, $body);

        $plans = $this->plans->find('NOT_FOUND');

        $this->assertInstanceOf(\Softpampa\Moip\Subscriptions\Resources\Plans::class, $plans);
        $this->assertEquals('GET', $this->client->getHttpMethod());
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/assinaturas/v1/plans/NOT_FOUND', $this->client->getUrl());
    }

}
