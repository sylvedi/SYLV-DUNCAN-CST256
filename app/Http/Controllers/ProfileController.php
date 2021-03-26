<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\UserService;
use App\Services\Business\SecurityService;
use App\Services\Utility\DatabaseException;
use App\Services\Utility\ILoggerService;
use App\Models\EducationModel;
use App\Models\SkillModel;
use App\Models\UserModel;
use App\Models\ExperienceModel;

/**
 * Manages profile data and display, including job experience, skills, and education.
 *
 *        
 */
class ProfileController extends Controller
{

    private $logger;
    
    /**
     * Instantiates the object with a database connection
     *
     * @param ILoggerService $logger
     */
    public function __construct(ILoggerService $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Display a profile by id
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function displayProfile(Request $request)
    {
        $this->logger->info("Enter ProfileController.displayProfile()");

        // Request parameters
        $id = $request->id;

        // Get all associated profile data
        $service = new UserService($this->logger);
        $user = $service->getProfile($id);
        $experience = $service->getExperience($id);
        $skills = $service->getSkills($id);
        $education = $service->getEducation($id);

        $this->logger->info("Exit ProfileController.displayProfile() with success.");

        // Return profile view
        return view("profile")->with([
            'user' => $user,
            'experience' => $experience,
            'skills' => $skills,
            'education' => $education,
            'id' => $id
        ]);
    }

    /**
     * Retrieve a form for editing a user profile by ID
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function displayProfileForEdit(Request $request)
    {
        $this->logger->info("Enter ProfileController.displayProfileForEdit()");

        // Request parameters
        $id = $request->id;

        // Check if the requester has permission to edit the profile
        $service = new UserService($this->logger);
        $sService = new SecurityService($this->logger);
        $canEdit = $sService->canEditUser($id, $request->session()
            ->get('UserID'));

        // Verify the requester can edit the profile
        if ($canEdit)
        {

            // Get the profile data
            $user = $service->getProfile($id);

            // Return the edit form with data
            $this->logger->info("Exit ProfileController.displayProfileForEdit() with success.", array(
                "profileId" => $user->getId(),
                "UserID" => $request->session()->get('UserID')
            ));
            return view("profileedit")->with([
                'user' => $user,
                'id' => $id
            ]);
        }
        else
        {
            // Return profile page with error
            $this->logger->info("Exit ProfileController.displayProfileForEdit() with failure: no permissions to modify user.", array(
                "profileId" => $id,
                "UserID" => $request->session()->get('UserID')
            ));
            return $this->displayProfile($request)->with([
                'message' => "No permissions to modify user."
            ]);
        }
    }

    /**
     * Retrieve a form for editing a user education entry by ID
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function displayEducationForEdit(Request $request)
    {
        $this->logger->info("Enter ProfileController.displayEducationForEdit()");

        // Request parameters
        $id = $request->id;

        // Check if the requester has permission to edit the profile
        $service = new UserService($this->logger);
        $sService = new SecurityService($this->logger);
        
        // Get the education data
        $data = $service->getSingleEducation($id);
        
        $canEdit = $sService->canEditUser($data->getUserId(), $request->session()
            ->get('UserID'));

        // Verify the requester can edit the profile
        if ($canEdit)
        {
            
            // Return the edit form with data
            $this->logger->info("Exit ProfileController.displayEducationForEdit() with success.", array(
                "educationid" => $data->getId(),
                "UserID" => $request->session()->get('UserID')
            ));
            return view("addEducation")->with([
                'education' => $data,
                'editing' => true
            ]);
        }
        else
        {
            // Return profile page with error
            $this->logger->info("Exit ProfileController.displayEducationForEdit() with failure: no permissions to modify user.", array(
                "educationid" => $id,
                "UserID" => $request->session()->get('UserID')
            ));
            $request->id = $data->getUserId();
            return $this->displayProfile($request)->with([
                'message' => "No permissions to modify user."
            ]);
        }
    }

    /**
     * Retrieve a form for editing a skill by ID
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function displaySkillForEdit(Request $request)
    {
        $this->logger->info("Enter ProfileController.displaySkillForEdit()");

        // Request parameters
        $id = $request->id;

        // Check if the requester has permission to edit the profile
        $service = new UserService($this->logger);
        $sService = new SecurityService($this->logger);
        
        // Get skill data
        $data = $service->getSingleSkill($id);
        
        $canEdit = $sService->canEditUser($data->getUserId(), $request->session()
            ->get('UserID'));

        // Verify the requester can edit the profile
        if ($canEdit)
        {
            
            // Return edit form with data
            $this->logger->info("Exit ProfileController.displaySkillForEdit() with success.", array(
                "skillid" => $data->getId(),
                "UserID" => $request->session()->get('UserID')
            ));
            return view("addSkill")->with([
                'skill' => $data,
                'editing' => true
            ]);
        }
        else
        {
            // Return profile page with error
            $this->logger->info("Exit ProfileController.displaySkillForEdit() with failure: no permissions to modify user.", array(
                "skillid" => $id,
                "UserID" => $request->session()->get('UserID')
            ));
            $request->id = $data->getUserId();
            return $this->displayProfile($request)->with([
                'message' => "No permissions to modify user."
            ]);
        }
    }

    /**
     * Retrieve a form for editing a user experience entry by ID
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function displayExperienceForEdit(Request $request)
    {
        $this->logger->info("Enter ProfileController.displayExperienceForEdit()");

        // GET parameters
        $id = $request->id;

        // Check if the requester has permission to edit the profile
        $service = new UserService($this->logger);
        $sService = new SecurityService($this->logger);
        
        // Get the experience data
        $data = $service->getSingleExperience($id);
        
        $canEdit = $sService->canEditUser($data->getUserid(), $request->session()
            ->get('UserID'));

        // Verify the requester can edit the profile
        if ($canEdit)
        {
            
            // Return edit form with data
            $this->logger->info("Exit ProfileController.displayExperienceForEdit() with success.", array(
                "experienceid" => $data->getId(),
                "UserID" => $request->session()->get('UserID')
            ));
            return view("addExperience")->with([
                'experience' => $data,
                'editing' => true
            ]);
        }
        else
        {
            // Return profile page with error
            $this->logger->info("Exit ProfileController.displayExperienceForEdit() with failure: no permissions to modify user.", array(
                "experienceid" => $id,
                "UserID" => $request->session()->get('UserID')
            ));
            $request->id = $data->getUserId();
            return $this->displayProfile($request)->with([
                'message' => "No permissions to modify user."
            ]);
        }
    }

    /**
     * Add an education entry from a form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function createEducation(Request $request)
    {
        $this->logger->info("Enter ProfileController.createEducation()");

        try
        {
            // Validate against model rules
            $request->validate(EducationModel::getRules());

            // Request parameters
            $userId = $request->session()->get('UserID');
            $school = $request->input('school');
            $description = $request->input('description');

            // Check if the user has permissions to edit the profile
            $sService = new SecurityService($this->logger);
            $canEdit = $sService->canEditUser($userId, $request->session()
                ->get('UserID'));

            // Verify the user has permission to edit the profile
            if ($canEdit)
            {

                // Perform education add operation
                $service = new UserService($this->logger);
                $education = new EducationModel(null, $userId, $school, $description);
                $result = $service->createEducation($education);

                // Verify operation result
                if ($result)
                {
                    // Return profile page
                    $this->logger->info("Exit ProfileController.createEducation() with success.");
                    $request->id = $userId;
                    return $this->displayProfile($request)->with([
                        'message' => 'The education was added.'
                    ]);
                }
                else
                {
                    // Return edit form with error
                    $this->logger->warn("Exit ProfileController.createEducation() with failure: there was an error adding the entry.", array(
                        "data" => $education
                    ));
                    return view("addEducation")->with([
                        'education' => $education,
                        'editing' => false,
                        'message' => "There was an error creating the education."
                    ]);
                }
            }
            else
            {
                // Return profile page with error
                $this->logger->warn("Exit ProfileController.createEducation() with failure: no permissions to modify user.", array(
                    "userId" => $request->session()->get('UserID')
                ));
                $request->id = $userId;
                return $this->displayProfile($request)->with([
                    'message' => 'No permissions to modify user.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return edit form with error
            return view("addEducation")->with([
                'education' => $education,
                'editing' => false,
                'message' => "There was an error creating the education."
            ]);
        }
    }

    /**
     * Add a skill entry from a form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function createSkill(Request $request)
    {
        $this->logger->info("Enter ProfileController.createSkill()");

        try
        {
            // Validate against model rules
            $request->validate(SkillModel::getRules());

            // Request parameters
            $userId = $request->session()->get('UserID');
            $years = $request->input('years');
            $description = $request->input('description');

            // Check if the user has permissions to edit the profile
            $sService = new SecurityService($this->logger);
            $canEdit = $sService->canEditUser($userId, $request->session()
                ->get('UserID'));

            // Verify the user has permission to edit the profile
            if ($canEdit)
            {

                // Perform skill add operation
                $service = new UserService($this->logger);
                $data = new SkillModel(null, $userId, $description, $years);
                $result = $service->createSkill($data);

                // Verify operation result
                if ($result)
                {
                    // Return profile page
                    $this->logger->info("Exit ProfileController.createSkill() with success.");
                    $request->id = $userId;
                    return $this->displayProfile($request)->with([
                        'message' => 'The skill was added.'
                    ]);
                }
                else
                {
                    // Return edit form with error
                    $this->logger->warn("Exit ProfileController.createSkill() with failure: there was an error adding the entry.", array(
                        "data" => $data
                    ));
                    return view("addSkill")->with([
                        'skill' => $data,
                        'editing' => false,
                        'message' => "There was an error creating the skill."
                    ]);
                }
            }
            else
            {
                // Return profile page with error
                $this->logger->warn("Exit ProfileController.createSkill() with failure: no permissions to modify user.", array(
                    "userId" => $request->session()->get('UserID')
                ));
                $request->id = $userId;
                return $this->displayProfile($request)->with([
                    'message' => 'No permissions to modify user.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return edit form with error
            return view("addSkill")->with([
                'skill' => $data,
                'editing' => false,
                'message' => "There was an error creating the skill."
            ]);
        }
    }

    /**
     * Add an experience entry from a form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function createExperience(Request $request)
    {
        $this->logger->info("Enter ProfileController.createExperience()");

        try
        {
            // Validate against model rules
            $request->validate(ExperienceModel::getRules());

            // Request parameters
            $userId = $request->session()->get('UserID');
            $jobtitle = $request->input('jobtitle');
            $company = $request->input('company');
            $description = $request->input('description');
            $currentjob = $request->input('currentjob');
            $startdate = $request->input('startdate');
            $enddate = $request->input('enddate');

            // Check if the user has permissions to edit the profile
            $sService = new SecurityService($this->logger);
            $canEdit = $sService->canEditUser($userId, $request->session()
                ->get('UserID'));

            // Verify the user has permission to edit the profile
            if ($canEdit)
            {

                // Perform experience add operation
                $service = new UserService($this->logger);
                $experience = new ExperienceModel(null, $userId, $company, $jobtitle, $description, $startdate, $enddate, $currentjob);
                $result = $service->createExperience($experience);

                // Verify operation result
                if ($result)
                {
                    // Return profile page
                    $this->logger->info("Exit ProfileController.createExperience() with success.");
                    $request->id = $userId;
                    return $this->displayProfile($request)->with([
                        'message' => 'The experience was added.'
                    ]);
                }
                else
                {
                    // Return edit form with error
                    $this->logger->warn("Exit ProfileController.createExperience() with failure: there was an error adding the entry.", array(
                        "data" => $experience
                    ));
                    return view("addEducation")->with([
                        'experience' => $experience,
                        'editing' => false,
                        'message' => "There was an error adding the entry."
                    ]);
                }
            }
            else
            {
                // Return profile page with error
                $this->logger->warn("Exit ProfileController.createExperience() with failure: no permissions to modify user.", array(
                    "userId" => $request->session()->get('UserID')
                ));
                $request->id = $userId;
                return $this->displayProfile($request)->with([
                    'message' => 'No permissions to modify user.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return edit form with error
            return view("addEducation")->with([
                'experience' => $experience,
                'editing' => false,
                'message' => "There was an error adding the entry."
            ]);
        }
    }

    /**
     * Update a user profile from a form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function updateUser(Request $request)
    {
        $this->logger->info("Enter ProfileController.updateUser()");

        try
        {
            // Validate against model rules
            $request->validate(UserModel::getRules());

            // Request parameters
            $userId = $request->input("id");
            $userUsername = $request->input('username');
            $userPassword = $request->input('password');

            $userFirstname = $request->input('firstname');
            $userLastname = $request->input('lastname');

            $userEmail = $request->input('email');

            $userCity = $request->input('city');
            $userState = $request->input('state');

            $userSuspended = $request->input('suspended');

            $userBirthday = $request->input('birthday');
            $userTagline = $request->input('tagline');
            $userPhoto = $request->input('photo');

            // Check if the user has permissions to edit the profile
            $sService = new SecurityService($this->logger);
            $canEdit = $sService->canEditUser($userId, $request->session()
                ->get('UserID'));

            // Verify the user has permission to edit the profile
            if ($canEdit)
            {

                // Perform user update operation
                $service = new UserService($this->logger);
                $user = new UserModel($userId, $userUsername, $userPassword, $userFirstname, $userLastname, $userEmail, $userCity, $userState, $userSuspended, $userBirthday, $userTagline, $userPhoto);
                $result = $service->updateUser($user);

                // Verify operation result
                if ($result)
                {
                    // Return profile page
                    $this->logger->info("Exit ProfileController.updateUser() with success.");
                    $request->id = $userId;
                    return $this->displayProfile($request)->with([
                        'message',
                        'The profile was updated.'
                    ]);
                }
                else
                {
                    // Return edit form with error.
                    $this->logger->warn("Exit ProfileController.updateUser() with failure: there was an error updating the user.", array(
                        "data" => $user
                    ));
                    return view("profileedit")->with([
                        'user' => $user,
                        'id' => $userId,
                        'message',
                        'There was an error updating the user.'
                    ]);
                }
            }
            else
            {
                // Return profile page with error
                $this->logger->warn("Exit ProfileController.updateUser() with failure: no permissions to modify user.", array(
                    "userId" => $request->session()->get('UserID')
                ));
                $request->id = $userId;
                return $this->displayProfile($request)->with([
                    'message' => 'No permissions to modify user.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return edit form with error.
            return view("profileedit")->with([
                'user' => $user,
                'id' => $userId,
                'message',
                'There was an error updating the user.'
            ]);
        }
    }

    /**
     * Update an education entry from a form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function updateEducation(Request $request)
    {
        $this->logger->info("Enter ProfileController.updateEducation()");

        try
        {
            // Validate against model rules
            $request->validate(EducationModel::getRules());

            // Request parameters
            $id = $request->input("id");
            $userId = $request->input('userId');
            $school = $request->input('school');
            $description = $request->input('description');

            // Check if the user has permissions to edit the profile
            $sService = new SecurityService($this->logger);
            $canEdit = $sService->canEditUser($userId, $request->session()
                ->get('UserID'));

            // Verify the user has permission to edit the profile
            if ($canEdit)
            {

                // Perform education update operation
                $service = new UserService($this->logger);
                $education = new EducationModel($id, $userId, $school, $description);
                $result = $service->updateEducation($education);

                // Verify operation result
                if ($result)
                {
                    // Return profile page
                    $this->logger->info("Exit ProfileController.updateEducation() with success.");
                    $request->id = $userId;
                    return $this->displayProfile($request)->with([
                        'message',
                        'The education was updated.'
                    ]);
                }
                else
                {
                    // Return edit form with error
                    $this->logger->warn("Exit ProfileController.updateEducation() with failure: there was an error updating this entry.", array(
                        "data" => $education
                    ));
                    return view("editprofileeducation")->with([
                        'education' => $education,
                        'editing' => true,
                        'message' => "There was an error updating this entry."
                    ]);
                }
            }
            else
            {
                // Return profile page with error
                $this->logger->warn("Exit ProfileController.updateEducation() with failure: no permissions to modify user.", array(
                    "userId" => $request->session()->get('UserID')
                ));
                $request->id = $userId;
                return $this->displayProfile($request)->with([
                    'message' => 'No permissions to modify user.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return edit form with error
            return view("editprofileeducation")->with([
                'education' => $education,
                'editing' => true,
                'message' => "There was an error updating this entry."
            ]);
        }
    }

    /**
     * Update a skill entry from a form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function updateSkill(Request $request)
    {
        $this->logger->info("Enter ProfileController.updateSkill()");

        try
        {
            // Validate against model rules
            $request->validate(SkillModel::getRules());

            // Request parameters
            $id = $request->input("id");
            $userId = $request->input('userId');
            $years = $request->input('years');
            $description = $request->input('description');

            // Check if the user has permissions to edit the profile
            $sService = new SecurityService($this->logger);
            $canEdit = $sService->canEditUser($userId, $request->session()
                ->get('UserID'));

            // Verify the user has permission to edit the profile
            if ($canEdit)
            {

                // Perform skill update operation
                $service = new UserService($this->logger);
                $data = new SkillModel($id, $userId, $description, $years);
                $result = $service->updateSkill($data);

                // Verify operation result
                if ($result)
                {
                    // Return profile page
                    $this->logger->info("Exit ProfileController.updateSkill() with success.");
                    $request->id = $userId;
                    return $this->displayProfile($request)->with([
                        'message',
                        'The skill was updated.'
                    ]);
                }
                else
                {
                    // Return edit form with error
                    $this->logger->warn("Exit ProfileController.updateSkill() with failure: there was an error updating this entry.", array(
                        "data" => $data
                    ));
                    return view("editprofileskill")->with([
                        'skill' => $data,
                        'editing' => true,
                        'message' => "There was an error updating this entry."
                    ]);
                }
            }
            else
            {
                // Return profile page with error
                $this->logger->warn("Exit ProfileController.updateSkill() with failure: no permissions to modify user.", array(
                    "userId" => $request->session()->get('UserID')
                ));
                $request->id = $userId;
                return $this->displayProfile($request)->with([
                    'message' => 'No permissions to modify user.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return edit form with error
            return view("editprofileskill")->with([
                'skill' => $data,
                'editing' => true,
                'message' => "There was an error updating this entry."
            ]);
        }
    }

    /**
     * Update an experience entry from a form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function updateExperience(Request $request)
    {
        $this->logger->info("Enter ProfileController.updateExperience()");

        try
        {
            // Validate against model rules
            $request->validate(ExperienceModel::getRules());

            // Request parameters
            $id = $request->input("id");
            $userId = $request->input('userId');
            $jobtitle = $request->input('jobtitle');
            $company = $request->input('company');
            $description = $request->input('description');
            $currentjob = ($request->input('currentjob') == null) ? false : true;
            $startdate = $request->input('startdate');
            $enddate = $request->input('enddate');

            // Check if the user has permissions to edit the profile
            $sService = new SecurityService($this->logger);
            $canEdit = $sService->canEditUser($userId, $request->session()
                ->get('UserID'));

            // Verify the user has permission to edit the profile
            if ($canEdit)
            {

                // Perform experience update operation
                $service = new UserService($this->logger);
                $experience = new ExperienceModel($id, $userId, $company, $jobtitle, $description, $startdate, $enddate, $currentjob);
                $result = $service->updateExperience($experience);

                // Verify operation result
                if ($result)
                {
                    // Return profile page
                    $this->logger->info("Exit ProfileController.updateExperience() with success.");
                    $request->id = $userId;
                    return $this->displayProfile($request)->with([
                        'message',
                        'The experience was updated.'
                    ]);
                }
                else
                {
                    // Return edit form with error
                    $this->logger->warn("Exit ProfileController.updateExperience() with failure: there was an error updating this entry.", array(
                        "data" => $experience
                    ));
                    return view("editprofileeducation")->with([
                        'experience' => $experience,
                        'editing' => true,
                        'message' => "There was an error updating this entry."
                    ]);
                }
            }
            else
            {
                // Return profile page with error
                $this->logger->warn("Exit ProfileController.updateExperience() with failure: no permissions to modify user.", array(
                    "userId" => $request->session()->get('UserID')
                ));
                $request->id = $userId;
                return $this->displayProfile($request)->with([
                    'message' => 'No permissions to modify user.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return edit form with error
            return view("editprofileeducation")->with([
                'experience' => $experience,
                'editing' => true,
                'message' => "There was an error updating this entry."
            ]);
        }
    }

    /**
     * Delete a user by ID
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function deleteUser(Request $request)
    {
        $this->logger->info("Enter ProfileController.deleteJob()");

        try
        {
            // Request parameters
            $id = $request->input("id");

            // Verify the requester is not trying to delete themselves
            if ($request->session()->get('UserID') != $id)
            {

                // Check if the user has permissions to edit the profile
                $sService = new SecurityService($this->logger);
                $canEdit = $sService->canEditUser($id, $request->session()
                    ->get('UserID'));

                // Verify the user has permission to edit the profile
                if ($canEdit)
                {

                    // Perform user delete operation
                    $service = new UserService($this->logger);
                    $result = $service->deleteUser($id);

                    // Verify operation result
                    if ($result)
                    {
                        // Return admin page
                        $this->logger->info("Enter ProfileController.deleteJob() with success.");
                        $a = new AdministrationController($this->logger);
                        return $a->displayAdminPage($request)->with([
                            'message' => 'User has been deleted.'
                        ]);
                    }
                    else
                    {
                        // Return admin page with error
                        $this->logger->warn("Enter ProfileController.deleteJob() with failure: there was an error deleting user.", array(
                            "id" => $id
                        ));
                        $a = new AdministrationController($this->logger);
                        return $a->displayAdminPage($request)->with([
                            'message' => 'There was an error deleting user.'
                        ]);
                    }
                }
                else
                {
                    // Return admin page with error
                    $this->logger->warn("Enter ProfileController.deleteJob() with failure: no permissions to modify user.", array(
                        "id" => $id,
                        "UserID" => $request->session()->get("UserID")
                    ));
                    $a = new AdministrationController($this->logger);
                    return $a->displayAdminPage($request)->with([
                        'message' => 'No permissions to modify user.'
                    ]);
                }
            }
            else
            {
                // Return admin page with error
                $this->logger->warn("Enter ProfileController.deleteJob() with failure: user attempted to perform the operation on themselves.", array(
                    "id" => $id,
                    "UserID" => $request->session()->get("UserID")
                ));
                $a = new AdministrationController($this->logger);
                return $a->displayAdminPage($request)->with([
                    'message' => 'You cannot perform this action on yourself.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return admin page with error
            $a = new AdministrationController($this->logger);
            return $a->displayAdminPage($request)->with([
                'message' => 'There was an error deleting user.'
            ]);
        }
    }

    /**
     * Delete an eduaction entry by ID
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function deleteEducation(Request $request)
    {
        $this->logger->info("Enter ProfileController.deleteEducation()");

        try
        {
            // Request parameters
            $id = $request->input("id");
            $userId = $request->session()->get('UserID');

            // Check if the user has permissions to edit the profile
            $sService = new SecurityService($this->logger);
            $uService = new UserService($this->logger);
            $data = $uService->getSingleEducation($id);
            $canEdit = $sService->canEditUser($data->getUserid(), $request->session()
                ->get('UserID'));

            // Verify the user has permission to edit the profile
            if ($canEdit)
            {

                // Perform education delete operation
                $service = new UserService($this->logger);
                $result = $service->deleteEducation($id);

                // Verify operation result
                if ($result)
                {
                    // Return profile page
                    $this->logger->info("Exit ProfileController.deleteEducation() with success.");
                    $request->id = $userId;
                    return $this->displayProfile($request)->with([
                        'message' => 'Education has been deleted.'
                    ]);
                }
                else
                {
                    // Return profile page with error
                    $this->logger->warn("Exit ProfileController.deleteEducation() with failure: there was an error deleting the entry.", array(
                        "id" => $id
                    ));
                    $request->id = $userId;
                    return $this->displayProfile($request)->with([
                        'message' => 'There was an error deleting education.'
                    ]);
                }
            }
            else
            {
                // Return profile page with error
                $this->logger->warn("Exit ProfileController.deleteEducation() with failure: no permissions to modify user.", array(
                    "userId" => $request->session()->get('UserID')
                ));
                $request->id = $userId;
                return $this->displayProfile($request)->with([
                    'message' => 'No permissions to modify user.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return profile page with error
            $request->id = $userId;
            return $this->displayProfile($request)->with([
                'message' => 'There was an error deleting education.'
            ]);
        }
    }

    /**
     * Delete a skill entry by ID
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function deleteSkill(Request $request)
    {
        $this->logger->info("Enter ProfileController.deleteSkill()");

        try
        {
            // Request parameters
            $id = $request->input("id");
            $userId = $request->session()->get('UserID');

            // Check if the user has permissions to edit the profile
            $sService = new SecurityService($this->logger);
            $uService = new UserService($this->logger);
            $data = $uService->getSingleSkill($id);
            $canEdit = $sService->canEditUser($data->getUserid(), $request->session()
                ->get('UserID'));

            // Verify the user has permission to edit the profile
            if ($canEdit)
            {

                // Perform skill delete operation
                $service = new UserService($this->logger);
                $result = $service->deleteSkill($id);

                // Verify operation result
                if ($result)
                {
                    // Return profile page
                    $this->logger->info("Exit ProfileController.deleteSkill() with success.");
                    $request->id = $userId;
                    return $this->displayProfile($request)->with([
                        'message' => 'Skill has been deleted.'
                    ]);
                }
                else
                {
                    // Return profile page with error
                    $this->logger->info("Exit ProfileController.deleteSkill() with failure: there was an error deleting the entry.", array(
                        "id" => $id
                    ));
                    $request->id = $userId;
                    return $this->displayProfile($request)->with([
                        'message' => 'There was an error deleting skill.'
                    ]);
                }
            }
            else
            {
                // Return profile page with error
                $this->logger->warn("Exit ProfileController.deleteSkill() with failure: no permissions to modify user.", array(
                    "userId" => $request->session()->get('UserID')
                ));
                $request->id = $userId;
                return $this->displayProfile($request)->with([
                    'message' => 'No permissions to modify user.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return profile page with error
            $request->id = $userId;
            return $this->displayProfile($request)->with([
                'message' => 'There was an error deleting skill.'
            ]);
        }
    }

    /**
     * Delete an experience entry by ID
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function deleteExperience(Request $request)
    {
        $this->logger->info("Enter ProfileController.deleteExperience()");

        try
        {
            // Request parameters
            $id = $request->input("id");
            $userId = $request->session()->get('UserID');

            // Check if the user has permissions to edit the profile
            $sService = new SecurityService($this->logger);
            $uService = new UserService($this->logger);
            $data = $uService->getSingleExperience($id);
            $canEdit = $sService->canEditUser($data->getUserid(), $request->session()
                ->get('UserID'));

            // Verify the user has permission to edit the profile
            if ($canEdit)
            {

                // Perform experience delete operation
                $service = new UserService($this->logger);
                $result = $service->deleteExperience($id);

                // Verify operation result
                if ($result)
                {
                    // Return profile page
                    $this->logger->info("Exit ProfileController.deleteExperience() with success.");
                    $request->id = $userId;
                    return $this->displayProfile($request)->with([
                        'message' => 'Experience has been deleted.'
                    ]);
                }
                else
                {
                    // Return profile page with error
                    $this->logger->info("Exit ProfileController.deleteExperience() with failure: there was an error deleting the entry.", array(
                        "id" => $id
                    ));
                    $request->id = $userId;
                    return $this->displayProfile($request)->with([
                        'message' => 'There was an error deleting experience.'
                    ]);
                }
            }
            else
            {
                // Return profile page with error
                $this->logger->warn("Exit ProfileController.deleteExperience() with failure: no permissions to modify user.", array(
                    "userId" => $request->session()->get('UserID')
                ));
                $request->id = $userId;
                return $this->displayProfile($request)->with([
                    'message' => 'No permissions to modify user.'
                ]);
        }}
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return profile page with error
            $request->id = $userId;
            return $this->displayProfile($request)->with([
                'message' => 'There was an error deleting experience.'
            ]);
        }
    }

}
