<?php
namespace App\Http\Controllers;

use App\Models\GroupModel;
use App\Services\Utility\DatabaseException;
use App\Services\Utility\ILoggerService;
use Illuminate\Http\Request;
use App\Services\Business\GroupService;

/**
 * Manages group related tasks such as adding and removing groups, and member management.
 *
 */
class GroupController extends Controller
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
     * Display all of the groups in the database
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function displayGroups(Request $request)
    {
        $this->logger->info("Enter GroupController.displayGroups()");

        // Get all groups
        $service = new GroupService($this->logger);
        $data = $service->getGroups();

        // Return groups page
        $this->logger->info("Exit GroupController.displayGroups()");
        return view('groups')->with([
            'groups' => $data
        ]);
    }

    /**
     * Display a single group in the database.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function displayGroup(Request $request)
    {
        $this->logger->info("Enter GroupController.displayGroup()");

        // Request parameters
        $id = $request->id;

        // Get group data
        $service = new GroupService($this->logger);
        $data = $service->getGroup($id);

        $this->logger->info("Exit GroupController.displayGroup() with success.");

        // Return group view page
        return view("groupPage")->with([
            'group' => $data,
            'id' => $id
        ]);
    }

    /**
     * Add a group from a form.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function addGroup(Request $request)
    {
        $this->logger->info("Enter GroupController.addGroup()");

        try
        {
            // Validate against model rules
            $request->validate(GroupModel::getRules());

            // Request parameters
            $name = $request->input('name');
            $userId = $request->session()->get('UserID');
            $description = $request->input('description');

            // Perform add operation
            $service = new GroupService($this->logger);
            $data = new GroupModel(null, $userId, $name, $description);
            $result = $service->createGroup($data);

            // Verify operation result
            if ($result)
            {
                // Return group view page
                $this->logger->info("Exit GroupController.addGroup() with success.");
                return $this->displayGroups($request)->with([
                    'message' => 'The group was added.'
                ]);
            }
            else
            {
                // Return group view page with error
                $this->logger->info("Exit GroupController.addGroup() with failure: there was an error creating the group.", array(
                    "data" => $data
                ));
                return $this->displayGroups($request)->with([
                    'message' => 'There was an error creating the group.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return group view page with error
            return $this->displayGroups($request)->with([
                'message' => 'There was an error creating the group.'
            ]);
        }
    }

    /**
     * Update a group from a form.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function updateGroup(Request $request)
    {
        $this->logger->info("Enter GroupController.updateGroup()");

        try
        {
            // Validate against model rules
            $request->validate(GroupModel::getRules());

            // Request parameters
            $id = $request->input('id');
            $name = $request->input('name');
            $description = $request->input('description');

            // Get group data
            $service = new GroupService($this->logger);
            $data = $service->getGroup($id);
            $userId = $request->session()->get('UserID');

            // Verify the requester is an admin of the group
            if ($data->getAdminid() == $userId)
            {

                // Perform update operation
                $data->setName($name);
                $data->setDescription($description);
                $result = $service->updateGroup($data);

                // Verify operation result
                if ($result)
                {
                    // Return group view page
                    $this->logger->info("Exit GroupController.updateGroup() with success.");
                    return $this->displayGroups($request);
                }
                else
                {
                    // Return group view page with error
                    $this->logger->info("Exit GroupController.updateGroup() with failure: there was an error updating the group.", array(
                        "data" => $data
                    ));
                    return $this->displayGroups($request)->with([
                        'message' => 'There was an error updating the group.'
                    ]);
                }
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return group view page with error
            return $this->displayGroups($request)->with([
                'message' => 'There was an error updating the group.'
            ]);
        }
    }

    /**
     * Delete a group from a form.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function deleteGroup(Request $request)
    {
        $this->logger->info("Enter GroupController.deleteGroup()");

        try
        {
            // Request parameters
            $id = $request->input("id");

            // Get group data
            $service = new GroupService($this->logger);
            $data = $service->getGroup($id);
            $userId = $request->session()->get('UserID');

            // Verify the requester is an admin of the group
            if ($data->getAdminid() == $userId)
            {

                // Perform delete operation
                $result = $service->deleteGroup($id);

                // Verify operation result
                if ($result)
                {
                    // Return group view page
                    $this->logger->info("Exit GroupController.deleteGroup() with success.");
                    return $this->displayGroups($request);
                }
                else
                {
                    // Return group view page with error
                    $this->logger->info("Exit GroupController.deleteGroup() with failure: there was an error deleting the group.", array(
                        "id" => $id
                    ));
                    return $this->displayGroups($request)->with([
                        'message' => 'There was an error deleting the group.'
                    ]);
                }
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return group view page with error
            return $this->displayGroups($request)->with([
                'message' => 'There was an error deleting the group.'
            ]);
        }
    }

    /**
     * Display a group for editing
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function displayGroupForEdit(Request $request)
    {
        $this->logger->info("Enter GroupController.displayGroupForEdit()");

        // Request parameters
        $id = $request->id;

        // Get group data
        $service = new GroupService($this->logger);
        $data = $service->getGroup($id);
        $userId = $request->session()->get('UserID');

        // Verify the requester is an admin of the group
        if ($data->getAdminid() == $userId)
        {

            // Return edit form with data
            $this->logger->info("Exit GroupController.displayGroupForEdit() with success.", array(
                "group" => $data,
                "UserID" => $userId
            ));
            return view("createGroup")->with([
                'group' => $data,
                'editing' => true
            ]);
        }
        else
        {
            // Return group view page with error
            $this->logger->info("Exit GroupController.displayGroupForEdit() with failure: no permissions to edit group.", array(
                "group" => $data,
                "UserID" => $userId
            ));
            return $this->displayGroups($request)->with([
                'message' => 'No permissions to edit group.'
            ]);
        }
    }

    /**
     * Join a user to a group
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function joinGroup(Request $request)
    {
        $this->logger->info("Enter GroupController.joinGroup()");

        try
        {
            // Request parameters
            $id = $request->input('GroupID');
            $uid = $request->input('UserID');

            // Perform group join operation
            $service = new GroupService($this->logger);
            $result = $service->joinGroup($uid, $id);

            // Verify operation result
            if ($result)
            {

                // Return group page
                $this->logger->info("Exit GroupController.joinGroup() with success.", array(
                    "group" => $id,
                    "UserID" => $uid
                ));
                $request->id = $id;
                return $this->displayGroup($request);
            }
            else
            {
                // Return group page with error
                $this->logger->info("Exit GroupController.joinGroup() with failure.", array(
                    "group" => $id,
                    "UserID" => $uid
                ));
                $request->id = $id;
                return $this->displayGroup($request)->with([
                    'message' => 'There was an error joining the group'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return group page with error
            $request->id = $id;
            return $this->displayGroup($request)->with([
                'message' => 'There was an error joining the group'
            ]);
        }
    }

    /**
     * Disassociate a user from a group
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function leaveGroup(Request $request)
    {
        $this->logger->info("Enter GroupController.leaveGroup()");

        try
        {
            // Request parameters
            $id = $request->input('GroupID');
            $uid = $request->input('UserID');

            // Perform group leave operation
            $service = new GroupService($this->logger);
            $result = $service->leaveGroup($uid, $id);

            // Verify operation result
            if ($result)
            {
                // Return group page
                $this->logger->info("Exit GroupController.leaveGroup() with success.", array(
                    "group" => $id,
                    "UserID" => $uid
                ));
                $request->id = $id;
                return $this->displayGroup($request);
            }
            else
            {
                // Return group page with error
                $this->logger->info("Exit GroupController.leaveGroup() with failure.", array(
                    "group" => $id,
                    "UserID" => $uid
                ));
                $request->id = $id;
                return $this->displayGroup($request)->with([
                    'message' => 'There was an error leaving the group'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return group page with error
            $request->id = $id;
            return $this->displayGroup($request)->with([
                'message' => 'There was an error leaving the group'
            ]);
        }
        
    }
    
}
