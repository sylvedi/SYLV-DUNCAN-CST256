<?php
namespace App\Services\Business;
use PDO;

/**
 * Contains methods relating to database access
 *
 *        
 */
class DataService
{

    /**
     * Get a connection to the database
     *
     * @return \PDO
     */
    public static function connect()
    {
        $servername = config("database.connections.mysql.host");
        $port = config("database.connections.mysql.port");
        $username = config("database.connections.mysql.username");
        $password = config("database.connections.mysql.password");
        $dbname = config("database.connections.mysql.database");
        $db = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }
}

