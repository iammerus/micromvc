<?php

namespace MicroPos\Core\Helpers;

use MicroPos\Core\Database;

/**
 * Access the application's configuration variables
 *
 */
class Config
{
    protected static $instance = null;

    /**
     * This is the raw config data after being fetched from database
     * @var array
     */
    protected $raw;

    /**
     * The parsed, meaningful config data
     * @var array
     */
    protected $data = [];

    /**
     * Database connections
     * @var DB
     */
    protected $db;

    /**
     * Name of database table with configuration data
     * @var string
     */
    protected $databaseTable = "system_config";

    public function __construct()
    {
        $this->db = Database::getHelper();

        $this->loadConfig();

        static::$instance = $this;
    }

    public static function getInstance()
    {
        if(!is_null(static::$instance)) {
            return static::$instance = new Config();
        } else {
            return static::$instance;
        }
    }

    /**
     * Add a configuration value to the application's database
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function add($key, $value)
    {
        if (Arr::has($this->data, $key)) {
            return;
        }

        $this->data[$key] = $value;

        return $this->persist();
    }

    /**
     * Persists changes to application's database
     */
    protected function persist()
    {
        $this->db->truncate($this->databaseTable);

        return $this->db->insert(
            $this->databaseTable, ["setting", "value"], $this->orderForDB()
        );
    }

    /**
     * Removes an entry from the config array
     * @param mixed $key
     * @return boolean
     */
    public function remove($key)
    {
        if (!Arr::has($this->data, $key)) {
            return;
        }

        $this->data = Arr::remove($this->data, $key);

        return $this->persist();
    }

    /**
     * Order the config data for insertion into the database
     * @return mixed
     */
    protected function orderForDB()
    {
        $data = [];

        foreach ($this->data as $key => $config) {
            $data[] = [
                $key,
                $config
            ];
        }

        return $data;
    }

    /**
     * Get a configuration value
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (!Arr::has($this->data, $key)) {
            return;
        }

        return $this->data[$key];
    }

    /**
     * Loads the configuration from database
     * @return bool
     */
    protected function loadConfig()
    {
        $this->raw = $this->db->selectAll($this->databaseTable);

        return $this->parseConfig();
    }

    /**
     * Parses the config data fetched from the database
     * @return boolean
     */
    protected function parseConfig()
    {
        $this->data = [];

        foreach ($this->raw as $config) {
            $this->data[$config[0]] = $config[1];
        }

        unset($this->raw);

        return true;
    }

}
