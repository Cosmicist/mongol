<?php namespace Flatline\Mongol\Auth\Reminders;

use Flatline\Mongol\Mongol;
use Illuminate\Auth\Reminders\ReminderRepositoryInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class ReminderRepository implements ReminderRepositoryInterface
{
    /**
     * The reminder database collection
     *
     * @var string
     */
    protected $collection;

    /**
     * The hashing key
     *
     * @var string
     */
    protected $hasKey;

    /**
     * Create a new mongo reminder repository instance.
     *
     * @return void
     */
    public function __construct($collection, $hashKey)
    {
        $this->collection = Mongol::connection()->getDB()->{$collection};
        $this->hashKey = $hashKey;
    }

    /**
     * Create a new reminder record and token
     *
     * @param Illuminate\Auth\RemindableInterface $user
     * @return string
     */
    public function create(RemindableInterface $user)
    {
        $email = $user->getReminderEmail();
        $token = $this->createNewToken($user);

        $this->collection->insert($this->getPayload($email, $token));

        return $token;
    }

    /**
    * Build the record payload for the table.
    *
    * @param string $email
    * @param string $token
    * @return array
    */
    protected function getPayload($email, $token)
    {
        return array('email' => $email, 'token' => $token);
    }

    /**
    * Determine if a reminder record exists and is valid.
    *
    * @param Illuminate\Auth\RemindableInterface $user
    * @param string $token
    * @return bool
    */
    public function exists(RemindableInterface $user, $token)
    {
        $email = $user->getReminderEmail();
        $reminder = $this->collection->findOne(array('email' => $email, 'token' => $token));

        return $reminder and ! $this->reminderExpired($reminder);
    }

    /**
    * Determine if the reminder has expired.
    *
    * @param array $reminder
    * @return bool
    */
    protected function reminderExpired($reminder)
    {
        $createdPlusHour = $reminder['_id']->getTimestamp() + 216000;

        return $createdPlusHour < time();
    }

    /**
    * Delete a reminder record by token.
    *
    * @param string $token
    * @return void
    */
    public function delete($token)
    {
        $this->collection->remove(array('token' => $token));
    }

    /**
    * Create a new token for the user.
    *
    * @param Illuminate\Auth\RemindableInterface $user
    * @return string
    */
    public function createNewToken(RemindableInterface $user)
    {
        $email = $user->getReminderEmail();
        $value = str_shuffle(sha1($email.spl_object_hash($this).microtime(true)));

        return hash_hmac('sha512', $value, $this->hashKey);
    }
}
