<?php namespace Flatline\Auth;

use Illuminate\Auth\GenericUser;

class MongolUser extends GenericUser {

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->attributes['_id'];
    }

    /**
     * Dynamically check if the user has the given attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
           return isset($this->attributes[$key]);
    }
}
