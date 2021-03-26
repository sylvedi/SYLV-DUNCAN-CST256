<?php
namespace App\Services\Data;

use App\Services\Utility\ILoggerService;
use PDO;
use App\Models\UserModel;

/**
 * Implements CRUD operations for the USERS table
 *
 */
class UserDAO implements IDataAccessObject
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
        $this->logger->info("Entering UserDAO.create()");

        // Build query
        $query = "INSERT INTO `users`(`id`, `FIRSTNAME`, `LASTNAME`, `EMAIL`, `CITY`, `STATE`, `SUSPENDED`, `BIRTHDAY`, `TAGLINE`, `PHOTO`) VALUES (NULL, :firstname, :lastname, :email, :city, :state, :suspended, :birthday, :tagline, :photo)";
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        // Check if the input data is for registration
        $isRegistration = (get_class($model) == "App\Models\RegistrationModel");

        // Global user properties
        $firstname = $model->getFirstname();
        $lastname = $model->getLastname();
        $email = $model->getEmail();
        $city = $model->getCity();
        $state = $model->getState();

        // Full user details properties
        if (! $isRegistration)
        {
            $suspended = $model->getSuspended();
            $birthday = $model->getBirthday();
            $tagline = $model->getTagline();
            $photo = $model->getPhoto();
        }
        else
        {
            $suspended = 0;
            $birthday = null;
            $tagline = null;
            $photo = null;
        }

        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':suspended', $suspended);
        $stmt->bindParam(':birthday', $birthday);
        $stmt->bindParam(':tagline', $tagline);
        $stmt->bindParam(':photo', $photo);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit UserDAO.create() with success");
            return $this->db->lastInsertId();
        }
        else
        {

            $this->logger->info("Exit UserDAO.create() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering UserDAO.readAll()");

        // Build query
        $query = "SELECT * FROM USERS WHERE 1";
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
                array_push($result, new UserModel($data['ID'], null, null, $data['FIRSTNAME'], $data['LASTNAME'], $data['EMAIL'], $data['CITY'], $data['STATE'], $data['SUSPENDED'], $data['BIRTHDAY'], $data['TAGLINE'], $data['PHOTO']));
            }
            $this->logger->info("Exit UserDAO.readAll() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit UserDAO.readAll() with failure.");
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
        $this->logger->info("Entering UserDAO.readById($id)");

        // Build query
        $query = "SELECT * FROM USERS WHERE ID=:id";
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
            $user = new UserModel($data['ID'], null, null, $data['FIRSTNAME'], $data['LASTNAME'], $data['EMAIL'], $data['CITY'], $data['STATE'], $data['SUSPENDED'], $data['BIRTHDAY'], $data['TAGLINE'], $data['PHOTO']);
            $this->logger->info("Exit UserDAO.readById($id) with success");
            return $user;
        }
        else
        {

            $this->logger->info("Exit UserDAO.readById($id) with failure. Data:{id: " . $id . "}");
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
        $this->logger->info("Entering UserDAO.readByModel()");

        $firstname = $model->getFirstname();
        $lastname = $model->getLastname();
        $email = $model->getEmail();
        $city = $model->getCity();
        $state = $model->getState();
        $suspended = $model->getSuspended();
        $birthday = $model->getBirthday();
        $tagline = $model->getTagline();
        $photo = $model->getPhoto();

        // Build the query
        $query = "SELECT * FROM USERS WHERE";

        $count = 0;
        if ($firstname != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " FIRSTNAME=:firstname";
            $count ++;
        }
        if ($lastname != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " LASTNAME=:lastname";
            $count ++;
        }
        if ($email != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " EMAIL=:email";
            $count ++;
        }
        if ($city != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " CITY=:city";
            $count ++;
        }
        if ($state != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " STATE=:state";
            $count ++;
        }
        if ($suspended != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " SUSPENDED=:suspended";
            $count ++;
        }
        if ($birthday != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " BIRTHDAY=:birthday";
            $count ++;
        }
        if ($tagline != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " TAGLINE=:tagline";
            $count ++;
        }
        if ($photo != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " PHOTO=:photo";
            $count ++;
        }

        $stmt = $this->db->prepare($query);

        // Bind parameters
        if ($firstname != null)
        {
            $stmt->bindParam(':firstname', $firstname);
        }
        if ($lastname != null)
        {
            $stmt->bindParam(':lastname', $lastname);
        }
        if ($email != null)
        {
            $stmt->bindParam(':email', $email);
        }
        if ($city != null)
        {
            $stmt->bindParam(':city', $city);
        }
        if ($state != null)
        {
            $stmt->bindParam(':state', $state);
        }
        if ($suspended != null)
        {
            $stmt->bindParam(':suspended', $suspended);
        }
        if ($birthday != null)
        {
            $stmt->bindParam(':birthday', $birthday);
        }
        if ($tagline != null)
        {
            $stmt->bindParam(':tagline', $tagline);
        }
        if ($photo != null)
        {
            $stmt->bindParam(':photo', $photo);
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
                array_push($result, new UserModel($data['ID'], null, null, $data['FIRSTNAME'], $data['LASTNAME'], $data['EMAIL'], $data['CITY'], $data['STATE'], $data['SUSPENDED'], $data['BIRTHDAY'], $data['TAGLINE'], $data['PHOTO']));
            }
            $this->logger->info("Exit UserDAO.readByModel() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit UserDAO.readByModel() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering UserDAO.update()");

        $id = $model->getId();
        $firstname = $model->getFirstname();
        $lastname = $model->getLastname();
        $email = $model->getEmail();
        $city = $model->getCity();
        $state = $model->getState();
        $suspended = ($model->getSuspended() ? 1 : 0);
        $birthday = $model->getBirthday();
        $tagline = $model->getTagline();
        $photo = $model->getPhoto();

        // Build the query
        $query = "UPDATE USERS SET ";

        $count = 0;
        if ($firstname != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `FIRSTNAME`=:firstname";
            $count ++;
        }
        if ($lastname != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `LASTNAME`=:lastname";
            $count ++;
        }
        if ($email != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `EMAIL`=:email";
            $count ++;
        }
        if ($city != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `CITY`=:city";
            $count ++;
        }
        if ($state != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `STATE`=:state";
            $count ++;
        }
        if ($suspended !== null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `SUSPENDED`=:suspended";
            $count ++;
        }
        if ($birthday != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `BIRTHDAY`=:birthday";
            $count ++;
        }
        if ($tagline != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `TAGLINE`=:tagline";
            $count ++;
        }
        if ($photo != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " `PHOTO`=:photo";
            $count ++;
        }

        $query = $query . " WHERE ID=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        if ($firstname != null)
        {
            $stmt->bindParam(':firstname', $firstname);
        }
        if ($lastname != null)
        {
            $stmt->bindParam(':lastname', $lastname);
        }
        if ($email != null)
        {
            $stmt->bindParam(':email', $email);
        }
        if ($city != null)
        {
            $stmt->bindParam(':city', $city);
        }
        if ($state != null)
        {
            $stmt->bindParam(':state', $state);
        }
        if ($suspended !== null)
        {
            $stmt->bindParam(':suspended', $suspended);
        }
        if ($birthday != null)
        {
            $stmt->bindParam(':birthday', $birthday);
        }
        if ($tagline != null)
        {
            $stmt->bindParam(':tagline', $tagline);
        }
        if ($photo != null)
        {
            $stmt->bindParam(':photo', $photo);
        }

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit UserDAO.update() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit UserDAO.update() with failure. Data:{" . $model . "}");
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
        $this->logger->info("Entering UserDAO.delete($id)");

        // Build the query
        $query = "DELETE FROM USERS WHERE ID = :id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit UserDAO.delete() with success");
            return true;
        }
        else
        {

            $this->logger->info("Exit UserDAO.delete() with failure");
            return false;
        }
    }

}

