<?php

namespace Softpampa\Moip\Tests\Resources\Preferences;

use stdClass;
use Softpampa\Moip\Moip;
use Softpampa\Moip\Tests\MoipTestCase;
use Softpampa\Moip\Preferences\Resources\Notifications;
use Illuminate\Support\Collection;

class NotificationsTest extends MoipTestCase {

    /**
     * @var Preferences\Resources\Notifications  $notifications
     */
    protected $notifications;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->notifications = $this->moip->preferences()->notifications();
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
        $this->addMockResponse(200, 'notification.json');

        $notification = $this->notifications;

        $notification->setTarget('http://requestb.in/1dhjesw1')
                     ->addEvent('PAYMENT.AUTHORIZED')
                     ->setMedia()
                     ->create();

        $this->assertInstanceOf(Notifications::class, $notification);
        $this->assertEquals('http://requestb.in/1dhjesw1', $notification->target);
        $this->assertContains('PAYMENT.AUTHORIZED', $notification->events);
        $this->assertEquals('POST', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/v2/preferences/notifications', $this->getHttpUrl());
    }

    /**
     * Get all notifications
     */
    public function testGetAllNotifications()
    {
        // Mock response
        $this->addMockResponse(200, 'notifications.json');

        $notifications = $this->notifications->all();

        $this->assertInstanceOf(Collection::class, $notifications);
        $this->assertInstanceOf(stdClass::class, $notifications->last());
        $this->assertEquals('NPR-9XSBSCS09RZ2', $notifications->last()->id);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/v2/preferences/notifications', $this->getHttpUrl());
    }

    /**
     * Find a notification by id
     */
    public function testFindANotificationById()
    {
        // Mock response
        $this->addMockResponse(200, 'notification.json');

        $notification = $this->notifications->find('NPR-DV61EEGGUFCQ');

        $this->assertInstanceOf(Notifications::class, $notification);
        $this->assertEquals('NPR-DV61EEGGUFCQ', $notification->id);
        $this->assertEquals('GET', $this->getHttpMethod());
        $this->assertEquals(200, $this->getHttpStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/v2/preferences/notifications/NPR-DV61EEGGUFCQ', $this->getHttpUrl());
    }

    /**
     * Delete a notification
     */
    public function testDeleteANotification()
    {
        // Mock response
        $this->addMockResponse(200, 'notification.json');
        $this->addMockResponse(204);

        $notification = $this->notifications->find('NPR-DV61EEGGUFCQ');
        $notification->delete();

        $this->assertEquals('DELETE', $this->getHttpMethod());
        $this->assertEquals(204, $this->getHttpStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/v2/preferences/notifications/NPR-DV61EEGGUFCQ', $this->getHttpUrl());
    }

    public function testDeleteANotificationById()
    {
        // Mock response
        $this->addMockResponse(204);

        $this->notifications->delete('NPR-DV61EEGGUFCQ');

        $this->assertEquals('DELETE', $this->getHttpMethod());
        $this->assertEquals(204, $this->getHttpStatusCode());
        $this->assertEquals(Moip::SANDBOX . '/v2/preferences/notifications/NPR-DV61EEGGUFCQ', $this->getHttpUrl());
    }

}
