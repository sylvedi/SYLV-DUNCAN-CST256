<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\SecurityService;
use App\Services\Business\UserService;
use App\Services\Utility\DatabaseException;
use App\Services\Utility\ILoggerService;
use App\Models\LoginModel;
use App\Models\UserModel;
use App\Models\RegistrationModel;

/**
 * Manages authentication related tasks such as login and logout
 */
class AuthenticationController extends Controller
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
     * Register a user from a form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function registerNewUser(Request $request)
    {
        $this->logger->info("Enter AuthenticationController.registerNewUser()");

        try
        {
            $request->validate(RegistrationModel::getRules());

            // Request parameters
            $userFirstName = $request->input('firstname');
            $userLastName = $request->input('lastname');

            $userCity = $request->input('city');
            $userState = $request->input('state');

            $userEmail = $request->input('email');

            $userUsername = $request->input('username');
            $userPassword = $request->input('password');

            // Register the user
            $reg = new RegistrationModel(null, $userUsername, $userPassword, $userFirstName, $userLastName, $userEmail, $userCity, $userState);
            $service = new UserService($this->logger);
            $success = $service->registerUser($reg);

            // Verify operation success
            if ($success == 1)
            {
                // Log in automatically
                $this->logger->info("Exit AuthenticationController.registerNewUser() with success.");
                return $this->loginUser($request)->with([
                    'message' => 'Welcome to the site.'
                ]);
            }
            else if ($success == 2)
            {

                // Clear password for logging
                $reg->setPassword(null);

                // Return to the registration page with error.
                $this->logger->info("Enter AuthenticationController.registerNewUser() with failure: the user already exists.", array(
                    "user" => $reg
                ));
                return view("registerandlogin")->with([
                    'user' => $reg,
                    'message' => "A user with that username already exists."
                ]);
            }
            else
            {

                // Clear password for logging
                $reg->setPassword(null);

                // Return to registration page with error.
                $this->logger->info("Enter AuthenticationController.registerNewUser() with failure: the registration operation failed.", array(
                    "user" => $reg
                ));
                return view("registerandlogin")->with([
                    'user' => $reg,
                    'message' => "There was an error during registration."
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return to the registration page with error.
            return view("registerandlogin")->with([
                'user' => $reg,
                'message' => "There was an error during registration."
            ]);
        }
    }

    /**
     * Process login from a form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function loginUser(Request $request)
    {
        $this->logger->info("Enter AuthenticationController.loginUser()");

        $request->validate(LoginModel::getRules());

        // Request parameters
        $userUsername = $request->input('username');
        $userPassword = $request->input('password');

        // Authenticate the user
        $service = new SecurityService($this->logger);
        $uService = new UserService($this->logger);

        $loginResult = $service->authenticate(new LoginModel(null, null, $userUsername, $userPassword));

        // Verify that login was successful
        if ($loginResult)
        {
            $userId = $loginResult->getUserid();
            $isAdmin = $service->isAdmin($userId);
            $userData = $uService->getProfile($userId);

            // Verify the user is not suspended
            if (! $userData->getSuspended())
            {
                // Clear the user password for storage in session
                $userData->setPassword(null);
                $this->logger->info("Exit AuthenticationController.loginUser() with success", array(
                    "UserID" => $userId,
                    "IsAdmin" => $isAdmin,
                    "user" => $userData
                ));
                $request->session()->put([
                    'LoggedIn' => true
                ]);
                $request->session()->put([
                    'UserID' => $userId
                ]);
                $request->session()->put([
                    'IsAdmin' => $isAdmin
                ]);
                $request->session()->put([
                    'user' => $userData
                ]);
                $w = new WelcomeController($this->logger);
                return $w->index($request);
            }
            else
            {
                $this->logger->info("Exit AuthenticationController.loginUser() with failure: the account has been suspended.", array(
                    "UserID" => $userId
                ));
                return view("registerandlogin")->with([
                    'message' => "Your account has been suspended.",
                    'user' => new UserModel(null, $userUsername, $userPassword, null, null, null, null, null, null, null, null, null),
                    'doLogin' => true
                ]);
            }
        }
        else
        {
            $this->logger->info("Exit AuthenticationController.loginUser() with failure: the username and password does not exist.", array(
                "username" => $userUsername
            ));
            return view("registerandlogin")->with([
                'message' => "That username and password does not exist.",
                'user' => new UserModel(null, $userUsername, $userPassword, null, null, null, null, null, null, null, null, null),
                'doLogin' => true
            ]);
        }
    }

    /**
     * Logout the user and clear session data
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function logout(Request $request)
    {
        $this->logger->info("Enter AuthenticationController.logout()");
        $request->session()->put([
            'LoggedIn' => null
        ]);
        $request->session()->put([
            'UserID' => null
        ]);
        $request->session()->put([
            'IsAdmin' => null
        ]);
        $request->session()->put([
            'user' => null
        ]);
        $this->logger->info("Exit AuthenticationController.logout()");
        return view("registerandlogin")->with([
            'message' => "You have been logged out.",
            'user' => new UserModel(null, null, null, null, null, null, null, null, null, null, null, null),
            'doLogin' => true
        ]);
    }

}
