<?php

namespace Flatline\Mongol;


class MongolDB extends \MongoDB
{
    /**
     * @var Client
     */
    public $conn;

    /**
     * Creates a new database
     *
     * @link http://www.php.net/manual/en/mongodb.construct.php
     * @param Client $conn Database connection.
     * @param string $name Database name.
     * @return MongolDB
     */
    public function __construct($conn, $name)
    {
        $this->conn = $conn;
        parent::__construct($conn, $name);
    }

    /**
     * Gets a collection
     *
     * @link http://www.php.net/manual/en/mongodb.get.php
     * @param string $name The name of the collection.
     * @return MongolCollection
     */
    public function __get($name)
    {
        return $this->selectCollection($name);
    }

    /**
     * Gets a collection
     *
     * @link http://www.php.net/manual/en/mongodb.selectcollection.php
     * @param string $name
     * @return MongolCollection
     */
    public function selectCollection($name)
    {
        return new MongolCollection($this, $name);
    }
}