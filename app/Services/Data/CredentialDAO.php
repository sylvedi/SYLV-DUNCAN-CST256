<?php
namespace App\Services\Data;

use App\Services\Utility\ILoggerService;
use PDO;
use App\Models\LoginModel;

/**
 * Implements CRUD operations for the CREDENTIALS table
 *
 */
class CredentialDAO implements IDataAccessObject
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
        $this->logger->info("Entering CredentialsDAO.create()");

        // Build query
        $query = "INSERT INTO `credentials`(`id`, `USERS_ID`, `USERNAME`, `PASSWORD`) VALUES (NULL, :userid, :username, :password)";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $userid = $model->getUserid();
        $username = $model->getUsername();
        $password = $model->getPassword();
        
        $stmt->bindParam(':userid', $userid);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit CredentialsDAO.create() with success");
            return $this->db->lastInsertId();
        }
        else
        {

            $this->logger->info("Exit CredentialsDAO.create() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering CredentialDAO.readAll()");

        // Build query
        $query = "SELECT * FROM CREDENTIALS WHERE 1";
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
                array_push($result, new LoginModel($data['ID'], $data['USERS_ID'], $data['USERNAME'], $data['PASSWORD']));
            }
            $this->logger->info("Exit CredentialDAO.readAll() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit CredentialDAO.readAll() with failure.");
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
        $this->logger->info("Entering CredentialsDAO.readById($id)");

        // Build query
        $query = "SELECT * FROM CREDENTIALS WHERE ID=:id";
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
            $result = new LoginModel($data['ID'], $data['USERS_ID'], $data['USERNAME'], $data['PASSWORD']);
            $this->logger->info("Exit CredentialsDAO.readById($id) with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit CredentialsDAO.readById($id) with failure.");
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
        $this->logger->info("Entering CredentialsDAO.readByModel()");

        $id = $model->getUserid();
        $username = $model->getUsername();
        $password = $model->getPassword();
        
        // Build the query
        $query = "SELECT * FROM CREDENTIALS WHERE";

        $count = 0;
        if ($id != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " USERS_ID=:id";
            $count ++;
        }
        if ($username != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " USERNAME=:username";
            $count ++;
        }
        if ($password != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " BINARY PASSWORD=:password";
            $count ++;
        }

        $stmt = $this->db->prepare($query);

        // Bind parameters
        if ($id != null)
        {
            $stmt->bindParam(':id', $id);
        }
        if ($username != null)
        {
            $stmt->bindParam(':username', $username);
        }
        if ($password != null)
        {
            $stmt->bindParam(':password', $password);
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
                array_push($result, new LoginModel($data['ID'], $data['USERS_ID'], $data['USERNAME'], $data['PASSWORD']));
            }
            $this->logger->info("Exit CredentialsDAO.readByModel() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit CredentialsDAO.readByModel() with failure.");
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
        $this->logger->info("Entering CredentialsDAO.update()");

        $id = $model->getId();
        $userid = $model->getUserid();
        $username = $model->getUsername();
        $password = $model->getPassword();

        // Build the query
        $query = "UPDATE CREDENTIALS SET ";

        $count = 0;
        if ($userid != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `USERS_ID`=:userid";
            $count ++;
        }
        if ($username != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `USERNAME`=:username";
            $count ++;
        }
        if ($password != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `PASSWORD`=:password";
            $count ++;
        }

        $query = $query . " WHERE ID=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        if ($userid != null)
        {
            $stmt->bindParam(':userid', $userid);
        }
        if ($username != null)
        {
            $stmt->bindParam(':username', $username);
        }
        if ($password != null)
        {
            $stmt->bindParam(':password', $password);
        }

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit CredentialsDAO.update() with success.");
            return $stmt;
        }
        else
        {

            $this->logger->info("Exit CredentialsDAO.update() with failure.");
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
        $this->logger->info("Entering CredentialsDAO.delete($id)");

        // Build the query
        $query = "DELETE FROM CREDENTIALS WHERE ID = :id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit CredentialsDAO.delete() with success.");
            return true;
        }
        else
        {

            $this->logger->info("Exit CredentialsDAO.delete() with failure.");
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
        $this->logger->info("Entering CredentialsDAO.deleteByUser($id)");

        // Build the query
        $query = "DELETE FROM CREDENTIALS WHERE USERS_ID = :id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit CredentialsDAO.deleteByUser() with success.");
            return true;
        }
        else
        {

            $this->logger->info("Exit CredentialsDAO.deleteByUser() with failure.");
            return false;
        }
    }

}