<?php namespace Flatline\Mongol;

class Client extends \MongoClient
{
    protected $dbname;
    
    public function __construct($config)
    {
        // Connect!
        parent::__construct($this->buildDSN($config));
    }

    /**
     * Get a database
     *
     * @param string $dbname
     * @return MongolDB
     * @deprecated
     */
    public function getDB($dbname = '')
    {
        return $this->selectDB($dbname);
    }
    
    private function buildDSN($config = NULL)
    {
        // If $config is an array, so build the dsn
        if (is_array($config)) {
            $default = array(
                'host'     => 'localhost',
                'username' => null,
                'password' => null,
                'database' => null,
                'port'     => 27017,
                'admin'    => false,
            );
            
            // Extend the default config
            $c = array_merge($default, $config);
            
            // Check for auth data
            $auth = '';
            if ($c['username'] and $c['password']) {
                $auth = "{$c['username']}:{$c['password']}@";
            }
            
            // Check for non-default port
            $port = '';
            if (is_int($c['port']) and $c['port'] != 27017) {
                $port = ":{$c['port']}";
            }
            
            // Save the DB name apart
            $this->dbname = $c['database'];

            $this->dname = $dbname = @$c['admin'] ? 'admin' : $c['database'];
            
            $dsn = "mongodb://$auth{$c['host']}$port/$dbname";

        } elseif (preg_match('/^mongodb:\/\//', $config)) {
            $dsn = $config;
            
            // Try to get the DB name
            if (preg_match('/\/([a-z0-9\-_]+)$/i', $config, $m)) {
                $this->dbname = $m[1];
            }
        } else {
            throw new \InvalidArgumentException("The Mongo configuration is invalid.");
        }
        
        return $dsn;
    }

    /**
     * Select database
     *
     * @param string $name
     * @return MongolDB
     */
    public function selectDB($name = '')
    {
        if (!$name) {
            $name = $this->dbname;
        }

        return new MongolDB($this, $name);
    }

    /**
     * Select database
     *
     * @param string $dbname
     * @return MongolDB
     */
    public function __get($dbname)
    {
        return $this->selectDB($dbname);
    }
}