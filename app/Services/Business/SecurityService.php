<?php
namespace App\Services\Business;

use App\Services\Utility\ILoggerService;
use App\Services\Utility\DatabaseException;
use PDOException;
use App\Models\LoginModel;
use App\Models\RegistrationModel;
use App\Models\UserModel;
use App\Services\Data\AdminDAO;
use App\Services\Data\UserDAO;
use App\Services\Data\CredentialDAO;

/**
 * Contains methods and logic for processing permissions and security-related functionality
 *
 *        
 */
class SecurityService
{

    private $db;
    private $logger;
    
    /**
     * Instantiates the object with a database connection
     *
     * @param ILoggerService $logger
     */
    public function __construct($logger)
    {
        $this->db = DataService::connect();
        $this->logger = $logger;
    }

    /**
     * Verify the username and password of a given user
     *
     * @param LoginModel|RegistrationModel|UserModel $user
     * @return mixed|boolean
     */
    public function authenticate($user)
    {
        $this->logger->info("Entering SecurityService.login()");

        // Read login data against database
        $service = new CredentialDAO($this->db, $this->logger);
        $result = $service->readByModel($user);

        // Verify result
        if (count($result) == 1)
        {
            $this->logger->info("Exit SecurityService.login() with success.");
            return $result[0];
        }
        else
        {
            $this->logger->warn("Exit SecurityService.login() with failure.");
            return false;
        }
    }

    /**
     * Verify that the given user ID is an admin
     *
     * @param int $id
     * @return boolean
     */
    public function isAdmin($id)
    {
        // Perform read operation
        $service = new AdminDAO($this->db, $this->logger);
        $result = $service->readById($id);

        // Verify result
        if ($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Verify that the given user has permission to edit the second user by ID
     *
     * @param int $id
     * @param int $userId
     * @return boolean
     */
    public function canEditUser($id, $userId)
    {
        // Check if the user is an admin or is self
        if ($userId == $id || $this->isAdmin($userId))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Suspend a user
     *
     * @param int $id
     * @return boolean
     */
    public function suspendUser($id)
    {
        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Get profile data
            $uService = new UserService($this->logger);
            $user = $uService->getProfile($id);

            // Update user data
            $user->setPassword(null); // keeps the password from updating
            $user->setSuspended(true);

            // Perform update operation
            $service = new UserDAO($this->db, $this->logger);
            $result = $service->update($user);

            // Verify result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit SecurityService.suspendUser() with success.");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit SecurityService.suspendUser() with failure: user suspension failed.");
                $this->db->rollBack();
                return false;
            }
        }
        catch (PDOException $e)
        {

            // Rollback SQL changes
            $this->db->rollBack();

            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Restore a user from a suspended state
     *
     * @param int $id
     * @return boolean
     */
    public function unsuspendUser($id)
    {
        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Get profile data
            $uService = new UserService($this->logger);
            $user = $uService->getProfile($id);

            // Update user data
            $user->setPassword(null); // keeps the password from updating
            $user->setSuspended(false);

            // Perform update operation
            $service = new UserDAO($this->db, $this->logger);
            $result = $service->update($user);

            // Verify result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit SecurityService.unsuspendUser() with success.");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit SecurityService.unsuspendUser() with failure: user restoration failed.");
                $this->db->rollBack();
                return false;
            }
        }
        catch (PDOException $e)
        {

            // Rollback SQL changes
            $this->db->rollBack();

            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

}

