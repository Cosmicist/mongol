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
}
