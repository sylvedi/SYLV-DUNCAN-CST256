<?php
namespace App\Services\Business;

use App\Services\Data\GroupDAO;
use App\Models\GroupModel;
use App\Services\Data\UserDAO;
use PDOException;
use App\Services\Utility\ILoggerService;
use App\Services\Utility\DatabaseException;

/**
 * Contains methods for managing job postings and applications
 *
 *        
 */
class GroupService
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
     * Get an array of all groups
     *
     * @return array|boolean|array
     */
    public function getGroups()
    {
        $this->logger->info("Enter GroupService.getGroups()");

        // Perform read operation
        $service = new GroupDAO($this->db, $this->logger);
        $result = $service->readAll();

        // Verify and return result
        if (! $result)
        {
            $this->logger->warn("Exit GroupService.getGroups() with no data");
            return array();
        }
        else
        {
            $this->logger->info("Exit GroupService.getGroups()", array(
                $result
            ));
            return $result;
        }
    }

    /**
     * Get a group and its members by id
     *
     * @param int $id
     * @return \App\Models\JobModel|boolean|\App\Models\JobModel
     */
    public function getGroup($id)
    {
        $this->logger->info("Enter GroupService.getGroup($id)");

        // Perform read operation
        $service = new GroupDAO($this->db, $this->logger);
        $result = $service->readById($id);

        // Verify operation result
        if (! $result)
        {
            $this->logger->warn("Exit GroupService.getGroup($id) with no data");
            return new GroupModel(null, null, null, null);
        }
        else
        {

            // Retrieve group members
            $members = $service->readGroupUsers($result->getId());
            $uService = new UserDAO($this->db, $this->logger);
            if ($members)
            {

                // Add members to the group model
                $newMem = $result->getMembers();
                foreach ($members as $val)
                {
                    $user = $uService->readById($val['USERS_ID']);
                    if ($user)
                    {
                        array_push($newMem, $user);
                    }
                }
                $result->setMembers($newMem);
            }

            $this->logger->info("Exit GroupService.getGroups()", ['group'=>$result]);
            return $result;
        }
    }

    /**
     * Create a group from a form
     *
     * @param GroupModel $job
     * @return boolean
     */
    public function createGroup($group)
    {
        $this->logger->info("Enter GroupService.createGroup()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Perform create operation
            $service = new GroupDAO($this->db, $this->logger);
            $result = $service->create($group);

            // Verify operation result
            if ($result)
            {

                // Add group admin association
                $result2 = $service->createUserGroup($group->getAdminid(), $result);

                // Verify operation result
                if ($result2)
                {
                    // Commit SQL changes
                    $this->logger->info("Exit GroupService.createGroup() with success.");
                    $this->db->commit();
                    return true;
                }
                else
                {
                    // Rollback SQL changes
                    $this->logger->warn("Exit GroupService.createGroup() with failure: group admin association creation failed.");
                    $this->db->rollBack();
                    return false;
                }
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit GroupService.createGroup() with failure: group creation failed.", ['group'=>$group]);
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
     * Update a group from a form
     *
     * @param GroupModel $job
     * @return boolean
     */
    public function updateGroup($group)
    {

        $this->logger->info("Enter GroupService.updateGroup()");
        
        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {
            // Perform update operation
            $service = new GroupDAO($this->db, $this->logger);
            $result = $service->update($group);

            // Verify operation result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->warn("Exit GroupService.updateGroup() with success.");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit GroupService.updateGroup() with failure: group update failed.", ['group'=>$group]);
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
     * Delete a group by id
     *
     * @param int $id
     * @return boolean
     */
    public function deleteGroup($id)
    {
        
        $this->logger->info("Enter GroupService.deleteGroup()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Read group members
            $service = new GroupDAO($this->db, $this->logger);
            $groupUsers = $service->readGroupUsers($id);

            // Remove group member associations
            if ($groupUsers)
            {
                foreach ($groupUsers as $user)
                {
                    $r = $service->deleteUserGroup($user['USERS_ID'], $id);
                    if (! $r)
                    {
                        // Rollback SQL changes
                        $this->logger->warn("Exit GroupService.deleteGroup() with failure: unable to remove user group association.", ['user'=>$user['USERS_ID'], 'group'=>$id]);
                        $this->db->rollBack();
                        return false;
                    }
                }
            }

            // Perform delete operation
            $result = $service->delete($id);

            // Verify result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit GroupService.deleteGroup() with success");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit GroupService.deleteGroup() with failure: group deletion failed.", ['user'=>$user['USERS_ID'], 'group'=>$id]);
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
     * Join a user to a group
     *
     * @param int $user
     * @param int $group
     * @return boolean
     */
    public function joinGroup($user, $group)
    {

        $this->logger->info("Enter GroupService.joinGroup()");
        
        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {
            // Read the groups the user is in
            $service = new GroupDAO($this->db, $this->logger);
            $userGroups = $service->readUserGroups($user);

            if ($userGroups || $userGroups == array())
            {

                // Verify that the user is not already in the group
                foreach ($userGroups as $gp)
                {
                    if ($group == $gp['GROUPS_ID'])
                    {
                        // Rollback SQL changes
                        $this->logger->warn("Exit GroupService.joinGroup() with failure: user was already a member of group.", ['user'=>$user, 'group'=>$group]);
                        $this->db->rollBack();
                        return false;
                    }
                }

                // Add user group association
                $result = $service->createUserGroup($user, $group);

                // Verify result
                if ($result)
                {
                    // Commit SQL changes
                    $this->logger->info("Exit GroupService.joinGroup() with success.");
                    $this->db->commit();
                    return true;
                }
                else
                {
                    // Rollback SQL changes
                    $this->logger->warn("Exit GroupService.joinGroup() with failure: join group failed.", ['user'=>$user, 'group'=>$group]);
                    $this->db->rollBack();
                    return false;
                }
            }
            else
            {
                // Rollback SQL changes
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
     * Remove a user from a group
     *
     * @param int $user
     * @param int $group
     * @return boolean
     */
    public function leaveGroup($user, $group)
    {
        
        $this->logger->info("Enter GroupService.leaveGroup()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {
            // Perform user leave operation
            $service = new GroupDAO($this->db, $this->logger);
            $result = $service->deleteUserGroup($user, $group);

            // Verify result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit GroupService.leaveGroup() with success.");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit GroupService.leaveGroup() with failure: leave group failed.", ['user'=>$user, 'group'=>$group]);
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

