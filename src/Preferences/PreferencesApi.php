<?php

/**
 * Moip Preferences API
 *
 * @since 0.0.1
 * @see http://dev.moip.com.br/assinaturas-api Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Preferences;

use Softpampa\Moip\Api;
use Softpampa\Moip\Preferences\Resources\Notifications;

class PreferencesApi extends Api {

    /**
     * @var  string  Moip API Version
     */
    protected $version = 'v2';

    /**
     * @var  string  Moip base URI
     */
    protected $uri = '';

    /**
     * Payments API
     *
     * @return Resources\Notifications
     */
    public function notifications()
    {
        return new Notifications($this);
    }

}
