<?php namespace Flatline\Auth;

use Flatline\Mongol\Mongol;
use Illuminate\Auth\GenericUser;
use Illuminate\Auth\Reminders\RemindableInterface;

class MongolUser extends GenericUser implements RemindableInterface {

    /**
     * The users collection
     *
     * @var MongoCollection
     */
    protected $collection;

    /**
     * Create a new MongolUser object.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);

        $collection = \Config::get('auth.table');

        $this->collection = Mongol::connection()->getDB()->{$collection};
    }

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
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    /**
     * Get the current user's password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Save the user to the database.
     *
     * @return boolean
     */
    public function save()
    {
        return $this->collection->save($this->attributes);
    }
}
