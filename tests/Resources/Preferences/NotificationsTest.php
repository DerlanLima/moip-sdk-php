<?php

namespace Softpampa\Moip\Tests\Resources\Preferences;

use stdClass;
use Softpampa\Moip\Moip;
use Softpampa\Moip\Tests\MoipTestCase;
use Softpampa\Moip\Preferences\Resources\Notifications;
use Illuminate\Support\Collection;

class NotificationsTest extends MoipTestCase {

    /**
     * @var \Softpampa\Moip\Preferences\Resources\Notifications
     */
    protected $notifications;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->notifications = $this->moip->preferences()->notifications();
        $this->client = $this->notifications->getClient();
    }

    public function testGetNotificationResourceInstance()
    {
        $this->assertInstanceOf(Notifications::class, $this->notifications);
    }

    /**
     * Create a new notification
     */
    public function testCreateANewNotification()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/notification');

        $notification = $this->notifications;

        $notification->setTarget('http://requestb.in/1dhjesw1')
                     ->addEvent('PAYMENT.AUTHORIZED')
                     ->setMedia()
                     ->create();

        $this->assertInstanceOf(Notifications::class, $notification);
        $this->assertEquals('http://requestb.in/1dhjesw1', $notification->target);
        $this->assertContains('PAYMENT.AUTHORIZED', $notification->events);
        $this->assertEquals('POST', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/v2/preferences/notifications', $this->client->getUrl());
    }

    /**
     * Get all notifications
     */
    public function testGetAllNotifications()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/notifications');

        $notifications = $this->notifications->all();

        $this->assertInstanceOf(Collection::class, $notifications);
        $this->assertInstanceOf(stdClass::class, $notifications->last());
        $this->assertEquals('NPR-9XSBSCS09RZ2', $notifications->last()->id);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/v2/preferences/notifications', $this->client->getUrl());
    }

    /**
     * Find a notification by id
     */
    public function testFindANotificationById()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/notification');

        $notification = $this->notifications->find('NPR-DV61EEGGUFCQ');

        $this->assertInstanceOf(Notifications::class, $notification);
        $this->assertEquals('NPR-DV61EEGGUFCQ', $notification->id);
        $this->assertEquals('GET', $this->client->getMethod());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/v2/preferences/notifications/NPR-DV61EEGGUFCQ', $this->client->getUrl());
    }

    /**
     * Delete a notification
     */
    public function testDeleteANotification()
    {
        // Mock response
        $this->client->addMockResponse('./tests/Mocks/notification');
        $this->client->addMockResponse(self::HTTP_NO_CONTENT);

        $notification = $this->notifications->find('NPR-DV61EEGGUFCQ');
        $notification->delete();

        $this->assertEquals('DELETE', $this->client->getMethod());
        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/v2/preferences/notifications/NPR-DV61EEGGUFCQ', $this->client->getUrl());
    }

    public function testDeleteANotificationById()
    {
        // Mock response
        $this->client->addMockResponse(self::HTTP_NO_CONTENT);

        $this->notifications->delete('NPR-DV61EEGGUFCQ');

        $this->assertEquals('DELETE', $this->client->getMethod());
        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('https://sandbox.moip.com.br/v2/preferences/notifications/NPR-DV61EEGGUFCQ', $this->client->getUrl());
    }

}
