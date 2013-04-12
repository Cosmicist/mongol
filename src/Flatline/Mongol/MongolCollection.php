<?php

namespace Flatline\Mongol;


class MongolCollection extends \MongoCollection
{
    /**
     * @var MongolDB
     */
    public $db;

    protected $model;

    /**
     * Creates a new collection
     *
     * @link http://www.php.net/manual/en/mongocollection.construct.php
     * @param MongolDB $db Parent database.
     * @param string $name Name for this collection.
     * @return MongolCollection
     */
    public function __construct($db, $name)
    {
        $this->db = $db;

        parent::__construct($db, $name);
    }

    /**
     * Gets a collection
     *
     * @link http://www.php.net/manual/en/mongocollection.get.php
     * @param string $name The next string in the collection name.
     * @return MongolCollection
     */
    public function __get($name)
    {
        return new static($this->db, $name);
    }

    /**
     * Use a model instead of an array for the results
     *
     * @param $class_or_instance The classname or instance to use as model
     * @return MongolCollection
     */
    public function setModel($class_or_instance)
    {
        $this->model = $class_or_instance;
        return $this;
    }

    /**
     * Queries this collection
     *
     * @link http://www.php.net/manual/en/mongocollection.find.php
     * @param array $query The fields for which to search.
     * @param array $fields Fields of the results to return.
     * @return MongolCursor
     */
    public function find($query = array(), $fields = array())
    {
        $cursor = new MongolCursor($this->db->conn, (string)$this, $query, $fields);

        if ($this->model) {
            $cursor->setModel($this->model);
        }

        return $cursor;
    }

    /**
     * Queries this collection, returning a single element
     *
     * @link http://www.php.net/manual/en/mongocollection.findone.php
     * @param array $query The fields for which to search.
     * @param array $fields Fields of the results to return.
     * @throws \InvalidArgumentException
     * @return array|null
     */
    public function findOne($query = array(), $fields = array())
    {
        $doc = parent::findOne($query, $fields);

        if ($this->model) {
            if (is_string($this->model)) {
                if (!class_exists($this->model)) {
                    throw new \InvalidArgumentException("{$this->model} is not a valid class.");
                }

                $class = $this->model;
                return new $class($doc);
            }

            return $this->model->fill($doc);
        }

        return $doc;
    }
}