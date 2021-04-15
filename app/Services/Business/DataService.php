<?php
namespace App\Services\Business;
use App\Services\Utility\ILoggerService;
use PDO;

/**
 * Contains methods relating to database access
 *
 *        
 */
class DataService
{
    private $logger;
        /**
     * Instantiates the object with a database connection
     *
     * @param ILoggerService $logger
     */
    public function __construct(ILoggerService $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get a connection to the database
     *
     * @return \PDO
     */
    public function connect()
    {
        $this->logger->info("Entering DataService.connect()");
        $servername = config("database.connections.mysql.host");
        $port = config("database.connections.mysql.port");
        $username = config("database.connections.mysql.username");
        $password = config("database.connections.mysql.password");
        $dbname = config("database.connections.mysql.database");
        $db = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $this->logger->info("Exiting DataService.connect()");
        return $db;
    }
}

