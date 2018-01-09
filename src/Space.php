<?php

namespace UW\Space;

use UWDOEM\Connection\Connection;

/**
 * Container class for data received from Space Web Service
 *
 * @package UW\Space
 */
class Space
{
    /** @var array */
    protected $attributes = [];

    /** @var \UWDOEM\Connection\Connection */
    protected static $connection;

    /**
     * @param string $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        if (!array_key_exists($attribute, $this->attributes)) {
	    $this->getFullData();
        }

	return $this->attributes[$attribute];
    }

    /**
     * @param Space $space
     * @param array $attrs
     * @return Space
     */
    protected static function fill(Space $space, array $attrs)
    {
        foreach ($attrs as $key => $value) {
            if (is_string($value) === true || is_bool($value) === true || $value === null) {
                $space->attributes[$key] = $value;
            }
	    if (is_array($value) === true) {
		foreach ($value as $a_key => $a_value) {
		    if (is_string($a_value) === true || is_bool($a_value) === true || $a_value === null) {
			$space->attributes[$key.$a_key] = $a_value;
		    }
		}
            }
		    
        }
        return $space;
    }

    /**
     * @param string $baseUrl
     * @return Connection
     * @throws \Exception If any of the required constants have not been set.
     */
    protected static function makeConnection($baseUrl)
    {
	$requiredConstants = ["UW_WS_BASE_PATH", "UW_WS_SSL_KEY_PATH", "UW_WS_SSL_CERT_PATH", "UW_WS_SSL_KEY_PASSWD"];
	foreach ($requiredConstants as $constant) {
	    if (defined($constant) === false) {
		throw new \Exception("You must define the constant $constant before using this library.");
	    }
	}
	return new Connection(
			      UW_WS_BASE_PATH . $baseUrl,
			      UW_WS_SSL_KEY_PATH,
			      UW_WS_SSL_CERT_PATH,
			      UW_WS_SSL_KEY_PASSWD,
			      defined("UW_WS_VERBOSE") && UW_WS_VERBOSE
			      );
    }

    /**
     * @return Connection
     */
    protected static function getConnection()
    {
	if (static::$connection === null) {
	    static::$connection = static::makeConnection("space/v1/");
	}
	return static::$connection;
    }

}
