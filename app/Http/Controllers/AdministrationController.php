<?php
namespace App\Http\Controllers;

use App\Services\Business\UserService;
use App\Services\Business\JobService;
use App\Services\Business\SecurityService;
use Illuminate\Http\Request;
use App\Services\Utility\DatabaseException;
use App\Services\Utility\ILoggerService;

/**
 * Manages administrative tasks such as user suspension and admin view display.
 *
 */
class AdministrationController extends Controller
{

    private $logger;
    
    /**
     * Instantiates the object with a database connection
     *
     * @param ILoggerService $logger
     */
    public function __construct(ILoggerService $logger)
    {
         // Create logger   
        $this->logger = $logger;
    }
    
    /**
     * Displays the administrator landing page if the logged in user has admin privileges.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function displayAdminPage(Request $request)
    {
        $this->logger->info("Enter AdministrationController.displayAdminPage()");
        $service = new SecurityService($this->logger);

        // Check if the user is an administrator
        if ($service->isAdmin($request->session()
            ->get('UserID')))
        {

            // Retrieve profiles and jobs for display on the admin page
            $uService = new UserService($this->logger);
            $allUsers = $uService->getProfiles();
            $jService = new JobService($this->logger);
            $allJobs = $jService->getJobs();

            // Return the view
            $this->logger->info("Exit AdministrationController.displayAdminPage() with success.", array(
                "UserID" => $request->session()->get('UserID')
            ));
            return view("admin")->with([
                'users' => $allUsers,
                'jobs' => $allJobs
            ]);
        }
        else
        {
            // Display the homepage
            $this->logger->warn("Exit AdministrationController.displayAdminPage() with failure: UserID is not that of an admin.", array(
                "UserID" => $request->session()->get('UserID')
            ));
            $w = new WelcomeController($this->logger);
            return $w->index($request)->with([
                'message' => 'You do not have permission to view this page.'
            ]);
        }
    }

    /**
     * Suspend a user by ID
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function suspendUser(Request $request)
    {
        $this->logger->info("Enter AdministrationController.suspendUser()");

        try
        {

            // Request parameters
            $userId = $request->input("id");

            // Verify that the requester is not trying to suspend themselves
            if ($request->session()->get('UserID') != $userId)
            {

                // Perform suspension
                $service = new SecurityService($this->logger);
                $result = $service->suspendUser($userId);

                // Verify operation success
                if ($result)
                {
                    // Return admin page with success message
                    $this->logger->info("Exit AdministrationController.suspendUser() with success.", array(
                        "UserID" => $request->session()->get('UserID'),
                        "User to suspend" => $userId
                    ));
                    return $this->displayAdminPage($request)->with([
                        'message' => 'The user was suspended.'
                    ]);
                }
                else
                {
                    // Return admin page with error message
                    $this->logger->warn("Exit AdministrationController.suspendUser() with failure: Suspend operation was not successful.", array(
                        "UserID" => $request->session()->get('UserID'),
                        "User to suspend" => $userId
                    ));

                    return $this->displayAdminPage($request)->with([
                        'message' => 'There was an error processing the request.'
                    ]);
                }
            }
            else
            {
                // Return admin page with error message
                $this->logger->info("Exit AdministrationController.suspendUser() with failure: user attemped to suspend themselves.", array(
                    "UserID" => $request->session()->get('UserID'),
                    "User to suspend" => $userId
                ));
                $request->message = 'You cannot perform this action on yourself.';
                return $this->displayAdminPage($request)->with([
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
            
            // Return admin page with error message
            return $this->displayAdminPage($request)->with([
                'message' => 'There was an error processing the request.'
            ]);
        }
    }

    /**
     * Restore a user by ID
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unsuspendUser(Request $request)
    {
        $this->logger->info("Enter AdministrationController.unsuspendUser()");

        try
        {
            // Request parameters
            $userId = $request->input("id");

            // Verify that the requester is not trying to unsuspend themselves.
            if (session('UserID') != $userId)
            {
                // Perform restoration
                $service = new SecurityService($this->logger);
                $result = $service->unsuspendUser($userId);

                // Verify operation success
                if ($result)
                {
                    // Return admin page with success message
                    $this->logger->info("Exit AdministrationController.unsuspendUser() with success.", array(
                        "UserID" => $request->session()->get('UserID'),
                        "User to restore" => $userId
                    ));
                    return $this->displayAdminPage($request)->with([
                        'message' => 'The user was restored.'
                    ]);
                }
                else
                {
                    // Return admin page with error message
                    $this->logger->info("Exit AdministrationController.unsuspendUser() with failure: Restore operation was not successful.", array(
                        "UserID" => $request->session()->get('UserID'),
                        "User to restore" => $userId
                    ));
                    return $this->displayAdminPage($request)->with([
                        'message' => 'There was an error processing the request.'
                    ]);
                }
            }
            else
            {
                // Return admin page with error message
                $this->logger->info("Exit AdministrationController.unsuspendUser() with failure: user attemped to restore themselves.", array(
                    "UserID" => $request->session()->get('UserID'),
                    "User to restore" => $userId
                ));
                return $this->displayAdminPage($request)->with([
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
            
            // Return admin page with error message
            return $this->displayAdminPage($request)->with([
                'message' => 'There was an error processing the request.'
            ]);
        }
    }

}
