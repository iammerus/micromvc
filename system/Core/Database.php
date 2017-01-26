<?php

namespace MicroPos\Core;

use Illuminate\Database\Capsule\Manager as Capsule;
use MicroPos\Core\Helpers\DB;
use PDO;

/**
 * Class Database
 *
 * @package \MicroPos\Core
 */
class Database extends Capsule
{
    /**
     * Instance of the PDO class
     * @var PDO|null
     */
    protected static $pdo;

    /**
     * Instance of the DB class
     * @var DB|null
     */
    protected static $db;


    public function __construct()
    {
        parent::__construct();

        $this->createPDO();
        $this->createHelper();
    }

    /**
     * Returns an instance of the Database helper class
     *
     * @return DB
     */
    public static function getHelper()
    {
        return static::$db;
    }

    /**
     * Create a new PDO connection
     *
     * @return void
     */
    protected function createPDO()
    {
        static::$pdo = new PDO($this->getDsn(), getenv("DB_USER"), getenv("DB_PASS"));
    }

    /**
     * Create a new DB object
     *
     * @return void
     */
    protected function createHelper()
    {
        static::$db = new DB();
    }

    /**
     * Get an instance of the PDO class
     *
     * @return PDO
     */
    public static function getPDOInstance()
    {
        return static::$pdo;
    }

    /**
     * Gets database connection dsn
     *
     * @return string
     */
    protected function getDsn()
    {
        return sprintf("mysql:host=%s;dbname=%s", getenv('DB_HOST'), getenv("DB_NAME"));
    }

    /**
     * Initialize Eloquent. This creates a new database connection and boots up the Eloquent ORM
     *
     * @return void
     */
    public function initialize()
    {
        $this->addConnection(
            [
                'driver' => 'mysql',
                'host' => getenv("DB_HOST"),
                'database' => getenv("DB_NAME"),
                'username' => getenv("DB_USER"),
                'password' => getenv("DB_PASS"),
                'charset' => "utf8",
                'collation' => "utf8_unicode_ci",
                'prefix' => '',
            ]
        );

        $this->setAsGlobal();

        $this->bootEloquent();
    }

}
