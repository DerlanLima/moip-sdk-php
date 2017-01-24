<?php

/**
 * Moip Subscription Customers API
 *
 * @since 0.0.3
 * @see http://dev.moip.com.br/referencia-api/#criar-preferncia-de-notificao-post Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Payments\Resources;

use Softpampa\Moip\MoipResource;

class Notifications extends MoipResource
{

    /**
     * Default Media
     *
     * @var string
     */
    const DEFAULT_MEDIA = 'WEBHOOK';

    /**
     * @var  string  $resource
     */
    protected $resource = 'preferences/notifications';

    /**
     * Save a notification preferences
     *
     * @return $this
     */
    public function save()
    {
        $this->populate($this->client->post('', [], $this->data));

        return $this;
    }

    /**
     * Delete a notification preference
     *
     * @param  int  $id
     * @return $this
     */
    public function delete($id = null)
    {
        if (! $id) {
            $id = $this->data->id;
        }

        return $this->client->delete('/{id}', ['id' => $id]);
    }

    /**
     * Get all notifications
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->client->get()->getResults();
    }

    /**
     * Find a notification by id
     *
     * @param  string  $id
     * @return $this;
     */
    public function find($id)
    {
        return $this->populate($this->client->get('/{id}', ['id' => $id]));
    }

    /**
     * Set WebHook URL
     *
     * @param  string  $url
     * @return $this
     */
    public function setWebHook($url)
    {
        $this->data->target = $url;
        $this->data->media = self::DEFAULT_MEDIA;

        return $this;
    }

    /**
     * Add a event name
     *
     * @param  string  $name
     * @return $this
     */
    public function addEvent($name)
    {
        $this->data->events[] = $name;

        return $this;
    }

    /**
     * Set a list of events name
     *
     * @param  array  $events
     * @return $this
     */
    public function setEventsList(array $events)
    {
        $this->data->events = array_merge($this->data->events, $events);

        return $this;
    }
}
