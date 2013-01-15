<?php namespace Flatline\Mongol;

class MongolManager {
    /**
     * The application instance.
     *
     * @var Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The active connection instances.
     *
     * @var array
     */
    protected $connections = array();

    /**
     * Create a new Mongo manager instance.
     *
     * @param  Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get a Mongo connection instance.
     *
     * @param  string  $name
     * @return Flatline\Mongol\Client
     */
    public function connection($name = null)
    {
        if ( ! isset($this->connections[$name]))
        {
            $this->connections[$name] = $this->createConnection($name);
        }

        return $this->connections[$name];
    }

    /**
     * Create the given connection by name.
     *
     * @param  string  $name
     * @return Flatline\Mongo\Client
     */
    protected function createConnection($name)
    {
        $config = $this->getConfig($name);

        $connection = new Client($config);

        $connection->connect();

        return $connection;
    }

    /**
     * Get the configuration for a connection.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig($name)
    {
        $name = $name ?: $this->getDefaultConnection();

        // To get the database connection configuration, we will just pull each of the
        // connection configurations and get the configurations for the given name.
        // If the configuration doesn't exist, we'll throw an exception and bail.
        $config = \Config::get("mongol::$name");

        if (is_null($config))
        {
            throw new \InvalidArgumentException("Mongo [$name] not configured.");
        }

        return $config;
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    protected function getDefaultConnection()
    {
        return 'default';
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->connection(), $method), $parameters);
    }
}