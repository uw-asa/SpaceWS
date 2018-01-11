<?php

namespace UW\SpaceWS;

/**
 * Container class for data received from Space Web Service
 *
 * @package UW\Site
 */
class Site extends Space
{

    function __construct($arg)
    {
        if (is_string($arg)) {
            $this->attributes['Code'] = $arg;
        } else {
            parent::__construct($arg);
        }
    }

}
