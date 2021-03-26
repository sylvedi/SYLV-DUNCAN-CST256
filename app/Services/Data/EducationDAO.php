<?php
namespace App\Services\Data;

use App\Services\Utility\ILoggerService;
use PDO;
use App\Models\EducationModel;

/**
 * Implements CRUD operations for the CREDENTIALS table
 *
 */
class EducationDAO implements IDataAccessObject
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
        $this->logger->info("Entering EducationDAO.create()");

        // Build query
        $query = "INSERT INTO `education`(`id`, `USERS_ID`, `SCHOOL`, `DESCRIPTION`) VALUES (NULL, :users_id, :school, :description)";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $id = $model->getUserid();
        $school = $model->getSchool();
        $description = $model->getDescription();
        
        $stmt->bindParam(':users_id', $id);
        $stmt->bindParam(':school', $school);
        $stmt->bindParam(':description', $description);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit EducationDAO.create() with success");
            return $this->db->lastInsertId();
        }
        else
        {

            $this->logger->info("Exit EducationDAO.create() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering EducationDAO.readAll()");

        // Build query
        $query = "SELECT * FROM EDUCATION WHERE 1";
        $stmt = $this->db->prepare($query);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {
            // Push result to an array of models
            $result = array();
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                array_push($result, new EducationModel($data['ID'], $data['USERS_ID'], $data['SCHOOL'], $data['DESCRIPTION']));
            }
            $this->logger->info("Exit EducationDAO.readAll() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit EducationDAO.readAll() with failure.");
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
        $this->logger->info("Entering EducationDAO.readById($id)");

        // Build query
        $query = "SELECT * FROM EDUCATION WHERE ID=:id";
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
            $result = new EducationModel($data['ID'], $data['USERS_ID'], $data['SCHOOL'], $data['DESCRIPTION']);
            $this->logger->info("Exit EducationDAO.readById($id) with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit EducationDAO.readById($id) with failure. Data:{id: " . $id . "}");
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
        $this->logger->info("Entering EducationDAO.readByModel()");

        $id = $model->getUserid();
        $school = $model->getSchool();
        $description = $model->getDescription();
        
        // Build the query
        $query = "SELECT * FROM EDUCATION WHERE";

        $count = 0;
        if ($id != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " USERS_ID=:id";
            $count ++;
        }
        if ($school != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " SCHOOL=:school";
            $count ++;
        }
        if ($description != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " DESCRIPTION=:description";
            $count ++;
        }

        $stmt = $this->db->prepare($query);

        // Bind parameters
        if ($id != null)
        {
            $stmt->bindParam(':id', $id);
        }
        if ($school != null)
        {
            $stmt->bindParam(':school', $school);
        }
        if ($description != null)
        {
            $stmt->bindParam(':description', $description);
        }

        // Execute query
        $result = $stmt->execute();

        // Verify results
        if ($result)
        {
            // Push results to an array of models
            $result = array();
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                array_push($result, new EducationModel($data['ID'], $data['USERS_ID'], $data['SCHOOL'], $data['DESCRIPTION']));
            }
            $this->logger->info("Exit EducationDAO.readByModel() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit EducationDAO.readByModel() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering EducationDAO.update()");

        $id = $model->getId();
        $school = $model->getSchool();
        $description = $model->getDescription();
        
        // Build the query
        $query = "UPDATE EDUCATION SET ";

        $count = 0;
        if ($school != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " SCHOOL=:school";
            $count ++;
        }
        if ($description != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " DESCRIPTION=:description";
            $count ++;
        }

        $query = $query . " WHERE ID=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        if ($school != null)
        {
            $stmt->bindParam(':school', $school);
        }
        if ($description != null)
        {
            $stmt->bindParam(':description', $description);
        }

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit EducationDAO.update() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit EducationDAO.update() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering EducationDAO.delete($id)");

        // Build the query
        $query = "DELETE FROM EDUCATION WHERE ID = :id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit EducationDAO.delete() with success");
            return true;
        }
        else
        {

            $this->logger->info("Exit EducationDAO.delete() with failure");
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
        $this->logger->info("Entering EducationDAO.deleteByUser($id)");

        // Build the query
        $query = "DELETE FROM EDUCATION WHERE USERS_ID = :id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit EducationDAO.deleteByUser() with success");
            return true;
        }
        else
        {

            $this->logger->info("Exit EducationDAO.deleteByUser() with failure");
            return false;
        }
    }

}