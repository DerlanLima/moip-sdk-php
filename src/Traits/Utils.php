<?php

namespace Softpampa\Moip\Traits;

trait Utils {

    /**
     * Compiles a string with markup into an interpolation.
     *
     * @param  string  $content
     * @param  array  $binds
     * @return string
     */
    protected function interpolate($content, $binds)
    {
        foreach ($binds as $key => $value) {
            $content = str_replace('{'.$key.'}', $value, $content);
        }

        return $content;
    }

}
