<?php namespace Flatline\Auth;

use Illuminate\Auth\GenericUser;
use Illuminate\Auth\Reminders\RemindableInterface;

class MongolUser extends GenericUser implements RemindableInterface {

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->attributes['_id'];
    }

    public function getReminderEmail()
    {
        return $this->email;
    }
}
