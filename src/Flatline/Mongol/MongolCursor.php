<?php

namespace Flatline\Mongol;


class MongolCursor extends \MongoCursor
{
    protected $model;

    /**
     * Create a new cursor
     *
     * @link http://www.php.net/manual/en/mongocursor.construct.php
     * @param resource $connection Database connection.
     * @param string $ns Full name of database and collection.
     * @param array $query Database query.
     * @param array $fields Fields to return.
     * @return MongolCursor Returns the new cursor
     */
    public function __construct($connection, $ns, array $query = array(), array $fields = array())
    {
        parent::__construct($connection, $ns, $query, $fields);
    }

    /**
     * Use a model instead of an array for the results
     *
     * @param $class_or_instance The classname or instance to use as model
     * @throws \InvalidArgumentException
     * @return MongolCollection
     */
    public function setModel($class_or_instance)
    {
        if (!is_object($class_or_instance) or (is_string($class_or_instance) and !class_exists($class_or_instance))) {
            throw new \InvalidArgumentException("{$class_or_instance} is not a valid class.");
        }

        $this->model = $class_or_instance;
    }

    /**
     * Returns the current element
     *
     * @link http://www.php.net/manual/en/mongocursor.current.php
     * @return mixed
     */
    public function current()
    {
        $current = parent::current();

        if ($this->model) {
            if (is_string($this->model)) {
                $class = $this->model;
                return new $class($current);
            }

            $current = $this->model->fill($current);
        }

        return $current;
    }

    /**
     * Return the next object to which this cursor points, and advance the cursor
     *
     * @link http://www.php.net/manual/en/mongocursor.getnext.php
     * @throws \MongoConnectionException
     * @throws \MongoCursorTimeoutException
     * @return mixed Returns the next object
     */
    public function getNext()
    {
        $this->next();
        return $this->current();
    }
}