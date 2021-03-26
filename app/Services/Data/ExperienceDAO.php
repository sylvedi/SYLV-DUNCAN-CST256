<?php
namespace App\Services\Data;

use App\Services\Utility\ILoggerService;
use PDO;
use App\Models\ExperienceModel;

/**
 * Implements CRUD operations for the EXPERIENCE table
 *
 */
class ExperienceDAO implements IDataAccessObject
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
        $this->logger->info("Entering ExperienceDAO.create()");

        // Build query
        $query = "INSERT INTO `experience`(`id`, `USERS_ID`, `COMPANY`, `JOBTITLE`, `DESCRIPTION`, `STARTDATE`, `ENDDATE`, `CURRENTJOB`) VALUES (NULL, :users_id, :company, :jobtitle, :description, :startdate, :enddate, :currentjob)";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $id = $model->getUserid();
        $company = $model->getCompany();
        $jobtitle = $model->getJobtitle();
        $description = $model->getDescription();
        $startdate = $model->getStartdate();
        $enddate = $model->getEnddate();
        $currentjob = $model->getCurrentjob();

        $stmt->bindParam(':users_id', $id);
        $stmt->bindParam(':company', $company);
        $stmt->bindParam(':jobtitle', $jobtitle);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':startdate', $startdate);
        $stmt->bindParam(':enddate', $enddate);
        $stmt->bindParam(':currentjob', $currentjob);

        // Execute query
        $result = $stmt->execute();

        // Verify results
        if ($result)
        {

            $this->logger->info("Exit ExperienceDAO.create() with success");
            return $this->db->lastInsertId();
        }
        else
        {

            $this->logger->info("Exit ExperienceDAO.create() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering ExperienceDAO.readAll()");

        // Build query
        $query = "SELECT * FROM EXPERIENCE WHERE 1";
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
                array_push($result, new ExperienceModel($data['ID'], $data['USERS_ID'], $data['COMPANY'], $data['JOBTITLE'], $data['DESCRIPTION'], $data['STARTDATE'], $data['ENDDATE'], $data['CURRENTJOB']));
            }
            $this->logger->info("Exit ExperienceDAO.readAll() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit ExperienceDAO.readAll() with failure.");
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
        $this->logger->info("Entering ExperienceDAO.readById($id)");

        // Build query
        $query = "SELECT * FROM EXPERIENCE WHERE ID=:id";
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
            $experience = new ExperienceModel($data['ID'], $data['USERS_ID'], $data['COMPANY'], $data['JOBTITLE'], $data['DESCRIPTION'], $data['STARTDATE'], $data['ENDDATE'], $data['CURRENTJOB']);
            $this->logger->info("Exit ExperienceDAO.readById($id) with success");
            return $experience;
        }
        else
        {

            $this->logger->info("Exit ExperienceDAO.readById($id) with failure. Data:{id: " . $id . "}");
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
        $this->logger->info("Entering ExperienceDAO.readByModel()");

        $id = $model->getUserid();
        $company = $model->getCompany();
        $jobtitle = $model->getJobtitle();
        $description = $model->getDescription();
        $startdate = $model->getStartdate();
        $enddate = $model->getEnddate();
        $currentjob = $model->getCurrentjob();

        // Build the query
        $query = "SELECT * FROM EXPERIENCE WHERE";

        $count = 0;
        if ($id != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " USERS_ID=:id";
            $count ++;
        }
        if ($company != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " COMPANY=:company";
            $count ++;
        }
        if ($jobtitle != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " JOBTITLE=:jobtitle";
            $count ++;
        }
        if ($description != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " DESCRIPTION=:description";
            $count ++;
        }
        if ($startdate != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " `STARTDATE`=:startdate";
            $count ++;
        }
        if ($enddate != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " `ENDDATE`=:enddate";
            $count ++;
        }
        if ($currentjob != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " `CURRENTJOB`=:currentjob";
            $count ++;
        }

        $stmt = $this->db->prepare($query);

        // Bind parameters
        if ($id != null)
        {
            $stmt->bindParam(':id', $id);
        }
        if ($company != null)
        {
            $stmt->bindParam(':company', $company);
        }
        if ($jobtitle != null)
        {
            $stmt->bindParam(':jobtitle', $jobtitle);
        }
        if ($description != null)
        {
            $stmt->bindParam(':description', $description);
        }
        if ($startdate != null)
        {
            $stmt->bindParam(':startdate', $startdate);
        }
        if ($enddate != null)
        {
            $stmt->bindParam(':enddate', $enddate);
        }
        if ($currentjob != null)
        {
            $stmt->bindParam(':currentjob', $currentjob);
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
                array_push($result, new ExperienceModel($data['ID'], $data['USERS_ID'], $data['COMPANY'], $data['JOBTITLE'], $data['DESCRIPTION'], $data['STARTDATE'], $data['ENDDATE'], $data['CURRENTJOB']));
            }
            $this->logger->info("Exit ExperienceDAO.readByModel() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit ExperienceDAO.readByModel() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering ExperienceDAO.update()");

        $id = $model->getId();
        $company = $model->getCompany();
        $jobtitle = $model->getJobtitle();
        $description = $model->getDescription();
        $startdate = $model->getStartdate();
        $enddate = $model->getEnddate();
        $currentjob = ($model->getCurrentjob() == true ? 1 : 0); // TODO there is a bug here where the column won't update to 0

        // Build the query
        $query = "UPDATE EXPERIENCE SET ";

        $count = 0;
        if ($company != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `COMPANY`=:company";
            $count ++;
        }
        if ($jobtitle != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `JOBTITLE`=:jobtitle";
            $count ++;
        }
        if ($description != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `DESCRIPTION`=:description";
            $count ++;
        }
        if ($startdate != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `STARTDATE`=:startdate";
            $count ++;
        }
        if ($enddate != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `ENDDATE`=:enddate";
            $count ++;
        }
        if ($currentjob != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `CURRENTJOB`=:currentjob";
            $count ++;
        }

        $query = $query . " WHERE ID=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        if ($company != null)
        {
            $stmt->bindParam(':company', $company);
        }
        if ($jobtitle != null)
        {
            $stmt->bindParam(':jobtitle', $jobtitle);
        }
        if ($description != null)
        {
            $stmt->bindParam(':description', $description);
        }
        if ($startdate != null)
        {
            $stmt->bindParam(':startdate', $startdate);
        }
        if ($enddate != null)
        {
            $stmt->bindParam(':enddate', $enddate);
        }
        if ($currentjob != null)
        {
            $stmt->bindParam(':currentjob', $currentjob);
        }

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit ExperienceDAO.update() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit ExperienceDAO.update() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering ExperienceDAO.delete($id)");

        // Build the query
        $query = "DELETE FROM EXPERIENCE WHERE ID = :id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit ExperienceDAO.delete() with success");
            return true;
        }
        else
        {

            $this->logger->info("Exit ExperienceDAO.delete() with failure");
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
        $this->logger->info("Entering ExperienceDAO.deleteByUser($id)");

        // Build the query
        $query = "DELETE FROM EXPERIENCE WHERE USERS_ID = :id";
        $stmt = $this->db->prepare($query);

        // Bind parameterss
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit ExperienceDAO.deleteByUser() with success");
            return true;
        }
        else
        {

            $this->logger->info("Exit ExperienceDAO.deleteByUser() with failure");
            return false;
        }
    }

}