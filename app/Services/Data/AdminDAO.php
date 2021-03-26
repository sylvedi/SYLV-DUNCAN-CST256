<?php
namespace App\Services\Data;

use App\Services\Utility\ILoggerService;
use PDO;

/**
 * Implements CRUD operations for the CREDENTIALS table
 *
 */
class AdminDAO implements IDataAccessObject
{

    private $db;
    private $logger;
    
    /**
     * Instantiates the object with a database connection
     *
     * @param PDO $db
     * @param ILoggerService $logger
     */
    public function __construct($db, $logger)
    {
        $this->db = $db;
        $this->logger = $logger;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Data\IDataAccessObject::create()
     */
    public function create($model)
    {
        $this->logger->info("Entering AdminDAO.create()");

        // Build query
        $query = "INSERT INTO `admins`(`id`, `USERS_ID`) VALUES (NULL, :users_id)";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $id = $model->getId();

        $stmt->bindParam(':users_id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit AdminDAO.create() with success");
            return $this->db->lastInsertId();
        }
        else
        {

            $this->logger->info("Exit AdminDAO.create() with failure.");
            return false;
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Data\IDataAccessObject::readAll()
     */
    public function readAll()
    {
        $this->logger->info("Entering AdminDAO.readAll()");

        // Build query
        $query = "SELECT * FROM ADMINS WHERE 1";
        $stmt = $this->db->prepare($query);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit AdminDAO.readAll() with success");
            return $stmt;
        }
        else
        {

            $this->logger->info("Exit AdminDAO.readAll() with failure.");
            return false;
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Data\IDataAccessObject::readById()
     */
    public function readById($id)
    {
        $this->logger->info("Entering AdminDAO.readById($id)");

        // Build query
        $query = "SELECT * FROM ADMINS WHERE USERS_ID=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result && $stmt->rowCount() == 1)
        {

            $this->logger->info("Exit AdminDAO.readById($id) with success");
            return $stmt;
        }
        else
        {

            $this->logger->info("Exit AdminDAO.readById($id) with failure.");
            return false;
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Data\IDataAccessObject::readByModel()
     */
    public function readByModel($model)
    {
        $this->logger->info("Entering AdminDAO.readByModel()");

        $id = $model->getId();

        // Build the query
        $query = "SELECT * FROM ADMINS WHERE";

        $count = 0;
        if ($id != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " USERS_ID=:id";
            $count ++;
        }

        $stmt = $this->db->prepare($query);

        // Bind parameters
        if ($id != null)
        {
            $stmt->bindParam(':id', $id);
        }

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit AdminDAO.readByModel() with success");
            return $stmt;
        }
        else
        {

            $this->logger->info("Exit AdminDAO.readByModel() with failure.");
            return false;
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Data\IDataAccessObject::update()
     */
    public function update($model)
    {
        $this->logger->info("Entering AdminDAO.update()");

        $id = $model->getId();
        
        // Build the query
        $query = "UPDATE ADMINS SET ";

        $count = 0;
        if ($id != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `USERS_ID`=:users_id";
            $count ++;
        }

        $query = $query . " WHERE USERS_ID=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        if ($id != null)
        {
            $stmt->bindParam(':users_id', $id);
        }

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit AdminDAO.update() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit AdminDAO.update() with failure.");
            return false;
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Data\IDataAccessObject::delete()
     */
    public function delete($id)
    {
        $this->logger->info("Entering AdminDAO.delete($id)");

        // Build the query
        $query = "DELETE FROM ADMINS WHERE USERS_ID = :id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        
        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit AdminDAO.delete() with success.");
            return true;
        }
        else
        {

            $this->logger->info("Exit AdminDAO.delete() with failure.");
            return false;
        }
    }

}