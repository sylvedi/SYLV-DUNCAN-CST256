<?php
namespace App\Services\Data;

use App\Services\Utility\ILoggerService;
use App\Models\JobModel;
use PDO;

/**
 * Implements CRUD operations for the CREDENTIALS table
 *
 */
class JobDAO implements IDataAccessObject
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
        $this->logger->info("Entering JobDAO.create()");

        // Build query and bind parameters
        $query = "INSERT INTO `jobs`(`id`, `COMPANIES_ID`, `TITLE`, `DESCRIPTION`) VALUES (NULL, :company_id, :title, :description)";

        $id = $model->getCompanyid();
        $title = $model->getTitle();
        $description = $model->getDescription();

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':company_id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);

        // Execute query and check result
        $result = $stmt->execute();

        if ($result)
        {

            $this->logger->info("Exit JobDAO.create() with success");
            return $this->db->lastInsertId();
        }
        else
        {

            $this->logger->warn("Exit JobDAO.create() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering JobDAO.readAll()");

        // Build query
        $query = "SELECT * FROM JOBS WHERE 1";
        $stmt = $this->db->prepare($query);

        // Execute query
        $result = $stmt->execute();

        // Verify results
        if ($result)
        {
            // Push results to an array of models
            $result = array();
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                array_push($result, new JobModel($data['ID'], $data['COMPANIES_ID'], $data['TITLE'], $data['DESCRIPTION']));
            }
            $this->logger->info("Exit JobDAO.readAll() with success");
            return $result;
        }
        else
        {

            $this->logger->warn("Exit JobDAO.readAll() with failure.");
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
        $this->logger->info("Entering JobDAO.readById($id)");

        // Build query
        $query = "SELECT * FROM JOBS WHERE ID=:id";
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
            $result = new JobModel($data['ID'], $data['COMPANIES_ID'], $data['TITLE'], $data['DESCRIPTION']);
            $this->logger->info("Exit JobDAO.readById($id) with success");
            return $result;
        }
        else
        {

            $this->logger->warn("Exit JobDAO.readById($id) with failure.");
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
        $this->logger->info("Entering JobDAO.readByModel()");

        $id = $model->getCompanyid();
        $title = $model->getTitle();
        $description = $model->getDescription();

        // Build the query
        $query = "SELECT * FROM JOBS WHERE";

        $count = 0;
        if ($id != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " COMPANIES_ID=:id";
            $count ++;
        }
        if ($title != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " TITLE=:title";
            $count ++;
        }
        if ($description != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " DESCRIPTION=:description";
            $count ++;
        }

        $stmt = $this->db->prepare($query);

        // Bind paramters
        if ($id != null)
        {
            $stmt->bindParam(':id', $id);
        }
        if ($title != null)
        {
            $stmt->bindParam(':title', $title);
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
            // Push results to an array of models
            $result = array();
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                array_push($result, new JobModel($data['ID'], $data['COMPANIES_ID'], $data['TITLE'], $data['DESCRIPTION']));
            }
            $this->logger->info("Exit JobDAO.readByModel() with success");
            return $result;
        }
        else
        {

            $this->logger->warn("Exit JobDAO.readByModel() with failure.");
            return false;
        }
    }
    
    /**
     * Search for a job using partial search methods
     * @param JobModel $model
     * @return array|boolean
     */
    public function searchByModelPartial($model, $and=false)
    {
        $this->logger->info("Entering JobDAO.searchByModelPartial()");
        
        $id = $model->getCompanyid();
        $title = $model->getTitle();
        $description = $model->getDescription();
        
        // Build the query
        $query = "SELECT * FROM JOBS WHERE";
        
        $count = 0;
        if ($id != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " COMPANIES_ID=:id";
            $count ++;
        }
        if ($title != null)
        {
            $query = $query . ($count > 0 ? (($and) ? " AND" : " OR") : "") . " TITLE LIKE :title";
            $count ++;
        }
        if ($description != null)
        {
            $query = $query . ($count > 0 ? (($and) ? " AND" : " OR") : "") . " DESCRIPTION LIKE :description";
            $count ++;
        }
        
        $stmt = $this->db->prepare($query);
        
        // Bind paramters
        if ($id != null)
        {
            $stmt->bindParam(':id', $id);
        }
        if ($title != null)
        {
            $title = "%" . $title . "%";
            $stmt->bindParam(':title', $title);
        }
        if ($description != null)
        {
            $description = "%" . $description . "%";
            $stmt->bindParam(':description', $description);
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
                array_push($result, new JobModel($data['ID'], $data['COMPANIES_ID'], $data['TITLE'], $data['DESCRIPTION']));
            }
            $this->logger->info("Exit JobDAO.searchByModelPartial() with success");
            return $result;
        }
        else
        {
            
            $this->logger->warn("Exit JobDAO.searchByModelPartial() with failure.");
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
        $this->logger->info("Entering JobDAO.update()");

        $id = $model->getId();
        $title = $model->getTitle();
        $description = $model->getDescription();

        // Build the query
        $query = "UPDATE JOBS SET ";

        $count = 0;
        if ($title != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `TITLE`=:title";
            $count ++;
        }
        if ($description != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `DESCRIPTION`=:description";
            $count ++;
        }

        $query = $query . " WHERE ID=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        if ($title != null)
        {
            $stmt->bindParam(':title', $title);
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

            $this->logger->info("Exit JobDAO.update() with success");
            return $result;
        }
        else
        {

            $this->logger->warn("Exit JobDAO.update() with failure.");
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
        $this->logger->info("Entering JobDAO.delete($id)");

        // Build the query
        $query = "DELETE FROM JOBS WHERE ID = :id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit JobDAO.delete() with success");
            return true;
        }
        else
        {

            $this->logger->warn("Exit JobDAO.delete() with failure");
            return false;
        }
    }

}