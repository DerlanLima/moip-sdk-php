<?php

/**
 * Moip Preferences API
 *
 * @since 0.0.1
 * @see http://dev.moip.com.br/assinaturas-api Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Preferences;

use Softpampa\Moip\MoipApi;
use Softpampa\Moip\Preferences\Resources\Notifications;

class PreferencesApi extends MoipApi {

    /**
     * Moip API Version
     *
     * @var string
     */
    protected $version = 'v2';

    /**
     * Moip base URI
     *
     * @var string
     */
    protected $path = '';

    /**
     * Payments API
     *
     * @return \Softpampa\Moip\Preferences\Resources\Notifications
     */
    public function notifications()
    {
        return new Notifications($this);
    }

}
