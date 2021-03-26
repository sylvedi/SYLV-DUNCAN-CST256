<?php
namespace App\Services\Data;

use App\Services\Utility\ILoggerService;
use PDO;
use App\Models\GroupModel;

/**
 * Implements CRUD operations for the SKILLS table
 *
 */
class GroupDAO implements IDataAccessObject
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
        $this->logger->info("Entering GroupDAO.create()");

        // Build query
        $query = "INSERT INTO `groups`(`ID`, `ADMIN_ID`, `NAME`, `DESCRIPTION`) VALUES (NULL, :adminid, :name, :description)";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $id = $model->getAdminid();
        $name = $model->getName();
        $description = $model->getDescription();

        $stmt->bindParam(':adminid', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit GroupDAO.create() with success");
            return $this->db->lastInsertId();
        }
        else
        {

            $this->logger->warn("Exit GroupDAO.create() with failure.");
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
        $this->logger->info("Entering GroupDAO.readAll()");

        // Build query
        $query = "SELECT * FROM GROUPS WHERE 1";
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
                array_push($result, new GroupModel($data['ID'], $data['ADMIN_ID'], $data['NAME'], $data['DESCRIPTION']));
            }
            $this->logger->info("Exit GroupDAO.readAll() with success");
            return $result;
        }
        else
        {

            $this->logger->warn("Exit GroupDAO.readAll() with failure.");
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
        $this->logger->info("Entering GroupDAO.readById($id)");

        // Build query
        $query = "SELECT * FROM GROUPS WHERE ID=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result && $stmt->rowCount() == 1)
        {
            // Return data as model
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $result = new GroupModel($data['ID'], $data['ADMIN_ID'], $data['NAME'], $data['DESCRIPTION']);
            $this->logger->info("Exit GroupDAO.readById($id) with success");
            return $result;
        }
        else
        {

            $this->logger->warn("Exit GroupDAO.readById($id) with failure.");
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
        $this->logger->info("Entering GroupDAO.readByModel()");

        $id = $model->getAdminid();
        $name = $model->getName();
        $description = $model->getDescription();

        // Build the query
        $query = "SELECT * FROM GROUPS WHERE";

        $count = 0;
        if ($id != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " ADMIN_ID=:id";
            $count ++;
        }
        if ($name != null)
        {
            $query = $query . ($count > 0 ? " AND" : "") . " NAME=:name";
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
        if ($description != null)
        {
            $stmt->bindParam(':description', $description);
        }
        if ($name != null)
        {
            $stmt->bindParam(':name', $name);
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
                array_push($result, new GroupModel($data['ID'], $data['ADMIN_ID'], $data['NAME'], $data['DESCRIPTION']));
            }
            $this->logger->info("Exit GroupDAO.readByModel() with success");
            return $result;
        }
        else
        {

            $this->logger->warn("Exit GroupDAO.readByModel() with failure.");
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
        $this->logger->info("Entering GroupDAO.update()");

        $id = $model->getId();
        $adminId = $model->getAdminid();
        $name = $model->getName();
        $description = $model->getDescription();

        // Build the query
        $query = "UPDATE GROUPS SET ";

        $count = 0;
        if ($adminId != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " ADMIN_ID=:adminid";
            $count ++;
        }
        if ($description != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " DESCRIPTION=:description";
            $count ++;
        }
        if ($name != null)
        {
            $query = $query . ($count > 0 ? " ," : "") . " NAME=:name";
            $count ++;
        }

        $query = $query . " WHERE ID=:id";

        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        if ($adminId != null)
        {
            $stmt->bindParam(':adminid', $adminId);
        }
        if ($name != null)
        {
            $stmt->bindParam(':name', $name);
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

            $this->logger->info("Exit GroupDAO.update() with success");
            return $result;
        }
        else
        {

            $this->logger->info("Exit GroupDAO.update() with failure.");
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
        $this->logger->info("Entering GroupDAO.delete($id)");

        // DELETE ALL USER GROUP ASSOCIATIONS FOR THIS GROUP

        // Build query
        $query = "DELETE FROM USERS_GROUPS WHERE GROUPS_ID = :id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {
            // Build the query
            $query = "DELETE FROM GROUPS WHERE ID = :id";
            $stmt = $this->db->prepare($query);

            // Bind parameters
            $stmt->bindParam(':id', $id);

            // Execute query
            $result2 = $stmt->execute();

            // Verify result
            if ($result2)
            {

                $this->logger->info("Exit GroupDAO.delete() with success");
                return true;
            }
            else
            {

                $this->logger->info("Exit GroupDAO.delete() with failure.");
                return false;
            }
        }
    }

    /**
     * Create an association between a user and group (add a user to the group's member list)
     *
     * @param int $user
     * @param int $group
     * @return boolean
     */
    public function createUserGroup($user, $group)
    {
        $this->logger->info("Entering GroupDAO.createUserGroup()");

        // Build query
        $query = "INSERT INTO `users_groups`(`USERS_ID`, `GROUPS_ID`) VALUES (:user, :group)";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':group', $group);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit GroupDAO.createUserGroup() with success");
            return true;
        }
        else
        {

            $this->logger->warn("Exit GroupDAO.createUserGroup() with failure.");
            return false;
        }
    }

    /**
     * Read groups that a user is a part of.
     *
     * @param int $user
     * @return mixed|boolean
     */
    public function readUserGroups($user)
    {
        $this->logger->info("Entering GroupDAO.readUserGroups()");

        // Build query
        $query = "SELECT * FROM USERS_GROUPS WHERE USERS_ID=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $user);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {
            // Push results to an array
            $result = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                array_push($result, $row);
            }

            $this->logger->info("Exit GroupDAO.readUserGroups() with success.");
            return $result;
        }
        else
        {

            $this->logger->warn("Exit GroupDAO.readUserGroups() with failure.");
            return false;
        }
    }

    /**
     * Read members of a group.
     *
     * @param int $group
     * @return mixed|boolean
     */
    public function readGroupUsers($group)
    {
        $this->logger->info("Entering GroupDAO.readGroupUsers()");

        // Build query
        $query = "SELECT * FROM USERS_GROUPS WHERE GROUPS_ID=:id";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $group);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result && $stmt->rowCount() > 0)
        {
            // Push results to an array
            $result = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                array_push($result, $row);
            }

            $this->logger->info("Exit GroupDAO.readGroupUsers() with success");
            return $result;
        }
        else
        {

            $this->logger->warn("Exit GroupDAO.readGroupUsers() with failure.");
            return false;
        }
    }

    /**
     * Remove an association between a user and group (remove a user from the group's member list)
     *
     * @param int $user
     * @param int $group
     * @return boolean
     */
    public function deleteUserGroup($user, $group)
    {
        $this->logger->info("Entering GroupDAO.deleteUserGroup()");

        // Build query
        $query = "DELETE FROM USERS_GROUPS WHERE USERS_ID=:user AND GROUPS_ID=:group";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':group', $group);

        // Execute query
        $result = $stmt->execute();

        // Verify result
        if ($result)
        {

            $this->logger->info("Exit GroupDAO.deleteUserGroup() with success");
            return true;
        }
        else
        {

            $this->logger->warn("Exit GroupDAO.deleteUserGroup() with failure.");
            return false;
        }
        
    }
    
}