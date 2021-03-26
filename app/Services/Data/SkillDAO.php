<?php
namespace App\Services\Data;

use App\Services\Utility\ILoggerService;
use PDO;
use App\Models\SkillModel;

/**
 * Implements CRUD operations for the SKILLS table
 *
 */
class SkillDAO implements IDataAccessObject
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
        $this->logger->info("Entering SkillDAO.create()");

        // Build query
        $query = "INSERT INTO `skills`(`id`, `USERS_ID`, `DESCRIPTION`, `YEARS`) VALUES (NULL, :users_id, :description, :years)";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $id = $model->getUserid();
        $description = $model->getDescription();
        $years = $model->getYears();
        
        $stmt->bindParam(':users_id', $id);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':years', $years);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit SkillDAO.create() with success");
            return $this->db->lastInsertId();
        }
        else
        {

            $this->logger->info("Exit SkillDAO.create() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering SkillDAO.readAll()");

        // Build query and bind parameters
        $query = "SELECT * FROM SKILLS WHERE 1";

        $stmt = $this->db->prepare($query);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {
            // Push results to an array of models
            $result = array();
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                array_push($result, new SkillModel($data['ID'], $data['USERS_ID'], $data['DESCRIPTION'], $data['YEARS']));
            }
            $this->logger->info("Exit SkillDAO.readAll() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit SkillDAO.readAll() with failure.");
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
        $this->logger->info("Entering SkillDAO.readById($id)");

        // Build query
        $query = "SELECT * FROM SKILLS WHERE ID=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result && $stmt->rowCount() == 1)
        {
            // Push result to a model
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $result = new SkillModel($data['ID'], $data['USERS_ID'], $data['DESCRIPTION'], $data['YEARS']);
            $this->logger->info("Exit SkillDAO.readById($id) with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit SkillDAO.readById($id) with failure. Data:{id: " . $id . "}");
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
        $this->logger->info("Entering SkillDAO.readByModel()");

        $id = $model->getUserid();
        $description = $model->getDescription();
        $years = $model->getYears();

        // Build the query
        $query = "SELECT * FROM SKILLS WHERE";

        $count = 0;
        if ($id != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " USERS_ID=:id";
            $count ++;
        }
        if ($description != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " DESCRIPTION=:description";
            $count ++;
        }
        if ($years != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " YEARS=:years";
            $count ++;
        }

        $stmt = $this->db->prepare($query);

        // Bind parameters
        if ($id != null)
        {
            $stmt->bindParam(':id', $id);
        }
        if ($description != null)
        {
            $stmt->bindParam(':description', $description);
        }
        if ($years != null)
        {
            $stmt->bindParam(':years', $years);
        }

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {
            // Push results to an array of models
            $result = array();
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                array_push($result, new SkillModel($data['ID'], $data['USERS_ID'], $data['DESCRIPTION'], $data['YEARS']));
            }
            $this->logger->info("Exit SkillDAO.readByModel() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit SkillDAO.readByModel() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering SkillDAO.update()");

        $id = $model->getId();
        $description = $model->getDescription();
        $years = $model->getYears();

        // Build the query
        $query = "UPDATE SKILLS SET ";

        $count = 0;
        if ($description != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " DESCRIPTION=:description";
            $count ++;
        }
        if ($years != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " YEARS=:years";
            $count ++;
        }

        $query = $query . " WHERE ID=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        if ($description != null)
        {
            $stmt->bindParam(':description', $description);
        }
        if ($years != null)
        {
            $stmt->bindParam(':years', $years);
        }

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit SkillDAO.update() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit SkillDAO.update() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering SkillDAO.delete($id)");

        // Build the query
        $query = "DELETE FROM SKILLS WHERE ID = :id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit SkillDAO.delete() with success");
            return true;
        }
        else
        {

            $this->logger->info("Exit SkillDAO.delete() with failure");
            return false;
        }
    }

    /**
     * Delete all entries associated with a certain user
     *
     * @param int $id
     * @return boolean
     */
    public function deleteByUser($id)
    {
        $this->logger->info("Entering SkillDAO.deleteByUser($id)");

        // Build the query
        $query = "DELETE FROM SKILLS WHERE USERS_ID = :id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit SkillDAO.deleteByUser() with success");
            return true;
        }
        else
        {

            $this->logger->info("Exit SkillDAO.deleteByUser() with failure");
            return false;
        }
    }

}