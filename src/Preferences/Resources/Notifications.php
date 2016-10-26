<?php

namespace Softpampa\Moip\Preferences\Resources;

use Softpampa\Moip\MoipResource;

class Notifications extends MoipResource {

    const DEFAULT_MEDIA = 'WEBHOOK';

    /**
     * @var  string  $resource
     */
    protected $resource = 'preferences/notifications';

    /**
     * Create a notification
     *
     * @return $this
     */
    public function create()
    {
        $this->populate($this->client->post('', [], $this->data));

        return $this;
    }

    /**
     * Delete a notification
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
     * Get all notifications
     *
     * @return Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->client->get()->getResults();
    }

    /**
     * Set a target
     *
     * @param  string  $url
     * @return $this
     */
    public function setTarget($url)
    {
        $this->data->target = $url;

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

    /**
     * Set a media type
     *
     * @param  string  $media
     * @return $this
     */
    public function setMedia($media = self::DEFAULT_MEDIA)
    {
        $this->data->media = $media;

        return $this;
    }

}
