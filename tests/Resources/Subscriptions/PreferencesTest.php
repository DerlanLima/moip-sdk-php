<?php

namespace Softpampa\Moip\Tests\Resources\Subscriptions;

use Illuminate\Support\Collection;
use Softpampa\Moip\Moip;
use Softpampa\Moip\Tests\MoipTestCase;
use Softpampa\Moip\Subscriptions\Resources\Preferences;

class PreferencesTest extends MoipTestCase {

    /**
     * Moip Assinaturas Preferences API
     *
     * @var  \Softpampa\Moip\Subscriptions\Resources\Preferences
     */
    private $preferences;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->preferences = $this->moip->subscriptions()->preferences();
        $this->client = $this->preferences->getClient();
    }

    /**
     * Test resource request
     *
     * @see http://dev.moip.com.br/assinaturas-api/#configurar-preferncias-de-notificao-post
     */
    public function testUserPreferencesResourceRequest()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/userpreferences');

        $preferences = $this->preferences->enableMerchantEmail()->save();

        $this->assertInstanceOf(Preferences::class, $preferences);
        $this->assertEquals('POST', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/assinaturas/v1/users/preferences', $this->client->getUrl());
    }

}
