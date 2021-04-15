<?php
namespace App\Services\Business;

use App\Models\UserModel;
use App\Models\LoginModel;
use App\Models\GroupModel;
use App\Models\RegistrationModel;
use App\Services\Data\UserDAO;
use App\Services\Data\CredentialDAO;
use App\Services\Data\EducationDAO;
use App\Services\Data\SkillDAO;
use App\Services\Data\ExperienceDAO;
use App\Services\Data\AdminDAO;
use PDOException;
use App\Services\Utility\DatabaseException;
use App\Services\Utility\ILoggerService;
use App\Models\ExperienceModel;
use App\Models\SkillModel;
use App\Models\EducationModel;
use App\Services\Data\GroupDAO;

/**
 * Contains methods to manage user related data including profiles, skills, education, and experience
 *
 *        
 */
class UserService
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
        $this->db = DataService::Connect()
        $this->logger = $logger;
    }

    /**
     * Registers the given user in the database
     *
     * @param RegistrationModel $user
     * @throws DatabaseException
     * @return boolean|number
     */
    public function registerUser(RegistrationModel $user)
    {
        $this->logger->info("Enter UserService.registerUser()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Perform read operation on username and password
            $cService = new CredentialDAO($this->db, $this->logger);
            $lm = new LoginModel(null, null, $user->getUsername(), $user->getPassword());
            $exists = $cService->readByModel($lm);

            // Verify that the username does not already exist
            if (! $exists || count($exists) == 0)
            {

                // Perform the create operation
                $service = new UserDAO($this->db, $this->logger);
                $uID = $service->create($user);

                // Verify result
                if (! $uID)
                {
                    // Rollback SQL changes
                    $this->logger->info("Exit UserService.registerUser() with failure: user creation failed.");
                    $this->db->rollBack();
                    return false;
                }
                else
                {

                    // Perform the credential create operation
                    $lm->setUserId($uID);
                    $cID = $cService->create($lm);

                    // Verify result
                    if ($cID)
                    {
                        // Commit SQL changes
                        $this->logger->info("Exit UserService.registerUser() with success");
                        $this->db->commit();
                        return 1;
                    }
                    else
                    {
                        // Rollback SQL changes
                        $this->logger->info("Exit UserService.registerUser() with failure: credential creation failed.");
                        $this->db->rollBack();
                        return false;
                    }
                }
            }
            else
            {
                // Rollback SQL changes
                $this->logger->info("Exit UserService.registerUser() with failure: user already exists.");
                $this->db->rollBack();
                return 2; // User already exists
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
     * Get a profile from the database by id
     *
     * @param int $id
     * @return NULL|boolean|\App\Models\UserModel
     */
    public function getProfile($id)
    {
        $this->logger->info("Enter UserService.getProfile()");

        // Perform read operation
        $service = new UserDAO($this->db, $this->logger);
        $resultUser = $service->readById($id);

        // Verify result
        if (! $resultUser)
        {
            $this->logger->warn("Exit UserService.getProfile() with no data");
            return null;
        }
        else
        {
            // Read username for the profile
            $cService = new CredentialDAO($this->db, $this->logger);
            $c = $cService->readByModel(new LoginModel(null, $id, null, null));

            // Add username to the profile data
            if (! $c || count($c) != 1)
            {
                $this->logger->warn("Exit UserService.getProfile() with no data");
                return null;
            }
            else
            {
                $resultUser->setUsername($c[0]->getUsername());
            }

            $this->logger->info("Exit UserService.getProfile() with success");
            return $resultUser;
        }
    }

    /**
     * Get the groups associated with a profile.
     *
     * @param int $id
     * @return NULL|array
     */
    public function getProfileGroups($id)
    {
        $this->logger->info("Enter UserService.getProfileGroups()");

        // Perform read operation
        $service = new GroupDAO($this->db, $this->logger);
        $result = $service->readUserGroups($id);

        // Verify result
        if (! $result)
        {
            return null;
        }
        else
        {

            // Add groups to array
            $data = array();
            foreach ($result as $val)
            {

                $group = $service->readById($val['GROUPS_ID']);

                if ($group)
                {
                    array_push($data, $group);
                }
            }

            $this->logger->info("Exit UserService.getProfileGroups() with success");
            return $data;
        }
    }

    /**
     * Get all profiles from the database
     *
     * @return array
     */
    public function getProfiles()
    {
        $this->logger->info("Enter UserService.getProfiles()");

        // Perform read operation
        $service = new UserDAO($this->db, $this->logger);
        $result = $service->readAll();

        // Verify result
        if (! $result)
        {
            return array();
        }
        else
        {

            $cService = new CredentialDAO($this->db, $this->logger);

            // Add usernames to each profile result
            // This is necessary since credentials are stored in a separate table and the two DAOs are separate.
            foreach ($result as $user)
            {
                $c = $cService->readByModel(new LoginModel(null, $user->getId(), null, null));
                if (count($c) == 1)
                {
                    $user->setUsername($c[0]->getUsername());
                }
            }

            $this->logger->info("Exit UserService.getProfiles() with success");
            return $result;
        }
    }

    /**
     * Get all experience entries associated with a user ID
     *
     * @param int $id
     * @return array|boolean|array
     */
    public function getExperience($id)
    {
        $this->logger->info("Enter UserService.getExperience()");

        // Perform read operation
        $service = new ExperienceDAO($this->db, $this->logger);
        $result = $service->readByModel(new ExperienceModel(null, $id, null, null, null, null, null, null));

        // Verify result
        if (! $result)
        {
            $this->logger->warn("Exit UserService.getExperience() with no data");
            return array();
        }
        else
        {
            $this->logger->info("Exit UserService.getExperience() with success");
            return $result;
        }
    }

    /**
     * Get an experience entry by ID
     *
     * @param int $id
     * @return \App\Models\ExperienceModel|boolean|\App\Models\ExperienceModel
     */
    public function getSingleExperience($id)
    {
        $this->logger->info("Enter UserService.getSingleExperience()");

        // Perform read operation
        $service = new ExperienceDAO($this->db, $this->logger);
        $result = $service->readById($id);

        // Verify result
        if (! $result)
        {
            $this->logger->warn("Exit UserService.getSingleExperience() with no data");
            return new ExperienceModel(null, null, null, null);
        }
        else
        {
            $this->logger->info("Exit UserService.getSingleExperience() with success");
            return $result;
        }
    }

    /**
     * Get all skills associated with a user ID
     *
     * @param int $id
     * @return array|boolean|array
     */
    public function getSkills($id)
    {
        $this->logger->info("Enter UserService.getSkills()");

        // Perform read operation
        $service = new SkillDAO($this->db, $this->logger);
        $result = $service->readByModel(new SkillModel(null, $id, null, null));

        // Verify result
        if (! $result)
        {
            $this->logger->warn("Exit UserService.getSkills() with no data");
            return array();
        }
        else
        {
            $this->logger->info("Exit UserService.getSkills() with success");
            return $result;
        }
    }

    /**
     * Get a skill by ID
     *
     * @param int $id
     * @return \App\Models\SkillModel|boolean|\App\Models\SkillModel
     */
    public function getSingleSkill($id)
    {
        $this->logger->info("Enter UserService.getSingleSkill()");

        // Perform read operation
        $service = new SkillDAO($this->db, $this->logger);
        $result = $service->readById($id);

        // Verify result
        if (! $result)
        {
            $this->logger->warn("Exit UserService.getSingleSkill() with no data");
            return new SkillModel(null, null, null, null);
        }
        else
        {
            $this->logger->info("Exit UserService.getSingleSkill() with success");
            return $result;
        }
    }

    /**
     * Get all education entries associated with a user ID
     *
     * @param int $id
     * @return array|boolean|array
     */
    public function getEducation($id)
    {
        $this->logger->info("Enter UserService.getEducation()");

        // Perform read operation
        $service = new EducationDAO($this->db, $this->logger);
        $result = $service->readByModel(new EducationModel(null, $id, null, null));

        // Verify result
        if (! $result)
        {
            $this->logger->warn("Exit UserService.getEducation() with no data");
            return array();
        }
        else
        {
            $this->logger->info("Exit UserService.getEducation() with success");
            return $result;
        }
    }

    /**
     * Get an education entry by ID
     *
     * @param int $id
     * @return \App\Models\EducationModel|boolean|\App\Models\EducationModel
     */
    public function getSingleEducation($id)
    {
        $this->logger->info("Enter UserService.getSingleEducation()");

        // Perform read operation
        $service = new EducationDAO($this->db, $this->logger);
        $result = $service->readById($id);

        // Verify result
        if (! $result)
        {
            $this->logger->warn("Exit UserService.getSingleEducation() with no data");
            return new EducationModel(null, null, null, null);
        }
        else
        {
            $this->logger->info("Exit UserService.getSingleEducation() with success");
            return $result;
        }
    }

    /**
     * Create an education entry
     *
     * @param EducationModel $education
     * @return boolean
     */
    public function createEducation($education)
    {
        $this->logger->info("Enter UserService.createEducation()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Perform create operation
            $service = new EducationDAO($this->db, $this->logger);
            $result = $service->create($education);

            // Verify result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit UserService.createEducation() with success");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.createEducation() with failure: education creation failed.");
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
     * Update an education entry
     *
     * @param EducationModel $education
     * @return boolean
     */
    public function updateEducation($education)
    {
        $this->logger->info("Enter UserService.updateEducation()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Perform update operation
            $service = new EducationDAO($this->db, $this->logger);
            $result = $service->update($education);

            // Verify result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit UserService.updateEducation() with success");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.updateEducation() with failure: education update failed.");
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
     * Delete an education entry
     *
     * @param int $id
     * @return boolean
     */
    public function deleteEducation($id)
    {
        $this->logger->info("Enter UserService.deleteEducation()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {
            // Perform delete operation
            $service = new EducationDAO($this->db, $this->logger);
            $result = $service->delete($id);

            // Verify result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit UserService.deleteEducation() with success");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.deleteEducation() with failure: education deletion failed.");
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
     * Create a skill
     *
     * @param SkillModel $skill
     * @return boolean
     */
    public function createSkill($skill)
    {
        $this->logger->info("Enter UserService.createSkill()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Perform create operation
            $service = new SkillDAO($this->db, $this->logger);
            $result = $service->create($skill);

            // Verify result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit UserService.createSkill() with success");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.createSkill() with failure: skill creation failed.");
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
     * Update a skill
     *
     * @param SkillModel $skill
     * @return boolean
     */
    public function updateSkill($skill)
    {
        $this->logger->info("Enter UserService.updateSkill()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Perform update operation
            $service = new SkillDAO($this->db, $this->logger);
            $result = $service->update($skill);

            // Verify result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit UserService.updateSkill() with success");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.updateSkill() with failure: skill update failed.");
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
     * Delete a skill
     *
     * @param SkillModel $id
     * @return boolean
     */
    public function deleteSkill($id)
    {
        $this->logger->info("Enter UserService.deleteSkill()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Perform delete operation
            $service = new SkillDAO($this->db, $this->logger);
            $result = $service->delete($id);

            // Verify result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit UserService.deleteSkill() with success");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.deleteSkill() with failure: skill deletion failed.");
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
     * Create an experience entry
     *
     * @param ExperienceModel $experience
     * @return boolean
     */
    public function createExperience($experience)
    {
        $this->logger->info("Enter UserService.createExperience()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Perform create operation
            $service = new ExperienceDAO($this->db, $this->logger);
            $result = $service->create($experience);

            // Verify result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit UserService.createExperience() with success");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.createExperience() with failure: experience creation failed.");
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
     * Update an experience entry
     *
     * @param ExperienceModel $experience
     * @return boolean
     */
    public function updateExperience($experience)
    {
        $this->logger->info("Enter UserService.updateExperience()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Perform update operation
            $service = new ExperienceDAO($this->db, $this->logger);
            $result = $service->update($experience);

            // Verify result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit UserService.updateExperience() with success");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.updateExperience() with failure: experience update failed.");
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
     * Delete an experience entry
     *
     * @param ExperienceModel $id
     * @return boolean
     */
    public function deleteExperience($id)
    {
        $this->logger->info("Enter UserService.deleteExperience()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Perform delete operation
            $service = new ExperienceDAO($this->db, $this->logger);
            $result = $service->delete($id);

            // Verify result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit UserService.deleteExperience() with success");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.deleteExperience() with failure: experience deletion failed.");
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
     * Update a user
     *
     * @param UserModel $user
     * @return boolean
     */
    public function updateUser($user)
    {
        $this->logger->info("Enter UserService.updateUser()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Get the credential associated with the user
            $id = $user->getId();
            $username = $user->getUsername();
            $password = $user->getPassword();
            $cred = new LoginModel(null, $id, $username, $password);
            $cService = new CredentialDAO($this->db, $this->logger);
            $cId = $cService->readByModel(new LoginModel(null, $id, null, null));

            // Set the username or password back if not present in update data
            if (count($cId) != 0)
            {
                $cred->setId($cId[0]->getId());
                if ($username == null) $cred->setUsername($cId[0]->getUsername());
                if ($password == null) $cred->setPassword($cId[0]->getPassword());
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.updateUser() with failure: credential read failed.");
                $this->db->rollBack();
                return false;
            }

            // Perform credentials update operation
            $result2 = $cService->update($cred);

            // Perform update operation
            $service = new UserDAO($this->db, $this->logger);
            $result = $service->update($user);

            // Verify results
            if ($result && $result2)
            {
                // Commit SQL changes
                $this->logger->info("Exit UserService.updateUser() with success.");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.updateUser() with failure: user update failed.", [
                    'userUpdate' => $result,
                    'credentialsUpdate' => $result2
                ]);
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
     * Delete a user
     *
     * @param UserModel $id
     * @return boolean
     */
    public function deleteUser($id)
    {
        $this->logger->info("Enter UserService.deleteUser()");

        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {
            $sCredential = new CredentialDAO($this->db, $this->logger);
            $sAdmin = new AdminDAO($this->db, $this->logger);
            $sUser = new UserDAO($this->db, $this->logger);
            $sExperience = new ExperienceDAO($this->db, $this->logger);
            $sEducation = new EducationDAO($this->db, $this->logger);
            $sSkill = new SkillDAO($this->db, $this->logger);
            $sGroup = new GroupDAO($this->db, $this->logger);

            // Get all groups owned by the user
            $groups = $sGroup->readByModel(new GroupModel(null, $id, null, null));

            // Delete all groups owned by the user
            if (count($groups) > 0)
            {
                foreach ($groups as $group)
                {
                    $groupDeleteResult = $sGroup->delete($group->getId());
                    if (! $groupDeleteResult)
                    {
                        // Rollback SQL changes
                        $this->logger->warn("Exit UserService.deleteUser($id) with failure: group deletion failed.", [
                            'group' => $group
                        ]);
                        $this->db->rollBack();
                        return false;
                    }
                }
            }

            // Get all groups user is a member of
            $memberships = $sGroup->readUserGroups($id);

            // Delete all user group associations for the user
            if (count($memberships) > 0)
            {
                foreach ($memberships as $membership)
                {
                    $memberDeleteResult = $sGroup->deleteUserGroup($id, $membership['GROUPS_ID']);
                    if (! $memberDeleteResult)
                    {
                        // Rollback SQL changes
                        $this->logger->warn("Exit UserService.deleteUser($id) with failure: group membership deletion failed.", [
                            'membership' => $membership
                        ]);
                        $this->db->rollBack();
                        return false;
                    }
                }
            }

            // Delete all admin associations with the user
            $adminDeleteResult = $sAdmin->delete($id);
            if (! $adminDeleteResult)
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.deleteUser($id) with failure: admin deletion failed.");
                $this->db->rollBack();
                return false;
            }

            // Delete all skills associated with the user
            $skillDeleteResult = $sSkill->deleteByUser($id);
            if (! $skillDeleteResult)
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.deleteUser($id) with failure: skill deletion failed.");
                $this->db->rollBack();
                return false;
            }

            // Delete all experience entries associated with the user
            $experienceDeleteResult = $sExperience->deleteByUser($id);
            if (! $experienceDeleteResult)
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.deleteUser($id) with failure: experience deletion failed.");
                $this->db->rollBack();
                return false;
            }

            // Delete all education entries associated with the user
            $educationDeleteResult = $sEducation->deleteByUser($id);
            if (! $educationDeleteResult)
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.deleteUser($id) with failure: education deletion failed.");
                $this->db->rollBack();
                return false;
            }

            // Delete all credentials associated with the user
            $credentialDeleteResult = $sCredential->deleteByUser($id);
            if (! $credentialDeleteResult)
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.deleteUser($id) with failure: credential deletion failed.");
                $this->db->rollBack();
                return false;
            }

            // Delete the user
            $userDeleteResult = $sUser->delete($id);
            if (! $userDeleteResult)
            {
                // Rollback SQL changes
                $this->logger->warn("Exit UserService.deleteUser($id) with failure: user deletion failed.");
                $this->db->rollBack();
                return false;
            }

            // Commit SQL changes
            $this->logger->info("Exit UserService.deleteUser($id) with success.");
            $this->db->commit();
            return true;
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

