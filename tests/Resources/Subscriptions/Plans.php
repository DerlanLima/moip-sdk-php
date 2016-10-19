<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use Softpampa\Moip\Tests\MoipTestCase;

class Plans extends MoipTestCase {

    protected $plans;

    public function setUp()
    {
        parent::setUp();

        $this->plans = $this->moip->subscriptions()->plans();
    }

    public function testGetAllPlansUrlAndMethodIsCorrect()
    {
        $plans = $this->plans->all();

        $this->assertEquals('GET', $this->client->getHttpMethod());
        $this->assertEquals('https://sandbox.moip.com.br/assinaturas/v1/plans', $this->client->getUrl());
    }

    public function testGetPlanUrlAndMethodIsCorrect()
    {
        $code = 'NOT_FOUND';
        $plans = $this->plans->find($code);

        $this->assertEquals('GET', $this->client->getHttpMethod());
        $this->assertEquals("https://sandbox.moip.com.br/assinaturas/v1/plans/{$code}", $this->client->getUrl());
    }

    public function testFindAPlan()
    {
        $body = Stream::factory('{"amount":990,"max_qty":1,"setup_fee":500,"interval":{"unit":"MONTH","length":1},"status":"ACTIVE","description":"DescriçãodoPlanoEspecial","name":"PlanoEspecial","billing_cycles":12,"code":"plan101","trial":{"enabled":true,"days":30,"hold_setup_fee":true},"payment_method":"CREDIT_CARD"}');

        $mock = new Mock([
            new Response(200, ['Content-Type' => 'application/json'], $body)
        ]);


        $this->attackMockSubscriber($mock);

        $plans = $this->plans->find('plan101');

        $this->assertEquals('plan101', $plans->code);
        $this->assertEquals('MONTH', $plans->interval->unit);

        $plans->setAmount(500);
        $plans->setTrial(false, 0, 0);

        $this->assertEquals(500, $plans->amount);
        $this->assertEquals(false, $plans->trial->enabled);
        $this->assertEquals(0, $plans->trial->days);
        $this->assertEquals(0, $plans->trial->hold_setup_fee);
    }

}
