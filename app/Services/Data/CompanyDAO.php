<?php
namespace App\Services\Data;

use App\Services\Utility\ILoggerService;
use PDO;

/**
 * Implements CRUD operations for the CREDENTIALS table
 *
 */
class CompanyDAO implements IDataAccessObject
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
        $this->logger->info("Entering CompanyDAO.create()");

        // Build query
        $query = "INSERT INTO `companies`(`id`, `NAME`) VALUES (NULL, :name)";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $name = $model->getName();

        $stmt->bindParam(':name', $name);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit CompanyDAO.create() with success");
            return $this->db->lastInsertId();
        }
        else
        {

            $this->logger->info("Exit CompanyDAO.create() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering CompanyDAO.readAll()");

        // Build query
        $query = "SELECT * FROM COMPANIES WHERE 1";
        $stmt = $this->db->prepare($query);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit CompanyDAO.readAll() with success");
            return $stmt;
        }
        else
        {

            $this->logger->info("Exit CompanyDAO.readAll() with failure.");
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
        $this->logger->info("Entering CompanyDAO.readById($id)");

        // Build query
        $query = "SELECT * FROM COMPANIES WHERE id=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result && $stmt->rowCount() == 1)
        {

            $this->logger->info("Exit CompanyDAO.readById($id) with success");
            return $stmt;
        }
        else
        {

            $this->logger->info("Exit CompanyDAO.readById($id) with failure. Data:{id: " . $id . "}");
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
        $this->logger->info("Entering CompanyDAO.readByModel()");

        $name = $model->getName();

        // Build the query
        $query = "SELECT * FROM COMPANIES WHERE";

        $count = 0;
        if ($name != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " NAME=:name";
            $count ++;
        }

        $stmt = $this->db->prepare($query);

        // Bind parameters
        if ($name != null)
        {
            $stmt->bindParam(':name', $name);
        }

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit CompanyDAO.readByModel() with success");
            return $stmt;
        }
        else
        {

            $this->logger->info("Exit CompanyDAO.readByModel() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering CompanyDAO.update()");

        $id = $model->getId();
        $name = $model->getName();

        // Build the query
        $query = "UPDATE COMPANIES SET ";

        $count = 0;
        if ($name != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `NAME`=:name";
            $count ++;
        }

        $query = $query . " WHERE id=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        if ($name != null)
        {
            $stmt->bindParam(':name', $name);
        }

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit CompanyDAO.update() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit CompanyDAO.update() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering CompanyDAO.delete($id)");

        // Build the query
        $query = "DELETE FROM COMPANIES WHERE id = :id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit CompanyDAO.delete() with success");
            return true;
        }
        else
        {

            $this->logger->info("Exit CompanyDAO.delete() with failure");
            return false;
        }
    }

}