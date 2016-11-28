<?php

/**
 * Moip Subscription Customers API
 *
 * @since 0.0.2
 * @see http://dev.moip.com.br/assinaturas-api/#assinantes Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Subscriptions\Resources;

use stdClass;
use Softpampa\Moip\MoipResource;

class Preferences extends MoipResource {

    /**
     * Resource name
     *
     * @var string
     */
    protected $resource = 'users/preferences';

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        parent::initialize();

        $this->data->notification = new stdClass;
    }

    /**
     * Save the user preferences
     *
     * @return $this
     */
    public function save()
    {
        $this->client->post('', [], $this->data);

        // if (! $response->hasErrors()) {
        //     $this->event->dispatch('SUBSCRIPTION.UPDATE', new SubscriptionsEvent($this->data));
        // }

        return $this;
    }

    /**
     * Set Web Hook URL
     *
     * @param  string  $url
     * @return $this
     */
    public function setWebHook($url)
    {
        $this->data->notification = new stdClass;
        $this->data->notification->webhook = new stdClass;
        $this->data->notification->webhook->url = $url;

        return $this;
    }

    /**
     * Enable merchant by email
     *
     * @return $this
     */
    public function enableMerchantEmail()
    {
        $this->data->email = new stdClass;
        $this->data->email->merchant = new stdClass;
        $this->data->email->merchant->enabled = true;

        return $this;
    }

    /**
     * Disable merchant by email
     *
     * @return $this
     */
    public function disableMerchantEmail()
    {
        $this->data->email = new stdClass;
        $this->data->email->merchant = new stdClass;

        $this->data->email->merchant->enabled = false;

        return $this;
    }

    /**
     * Enable customer receive payments emails
     *
     * @return $this
     */
    public function enableCustomerEmail()
    {
        $this->data->email = new stdClass;
        $this->data->email->customer = new stdClass;

        $this->data->email->customer->enabled = true;

        return $this;
    }

    /**
     * Disable customer receive payments emails
     *
     * @return $this
     */
    public function disableCustomerEmail()
    {
        $this->data->email = new stdClass;
        $this->data->email->customer = new stdClass;

        $this->data->email->customer->enabled = false;

        return $this;
    }
}
