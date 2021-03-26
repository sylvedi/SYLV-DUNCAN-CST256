<?php

namespace App\Http\Controllers;
use App\Services\Business\UserService;
use App\Models\DTO;
use Exception;
use App\Models\ProfileModel;
use App\Services\Utility\ILoggerService;

class UsersRestController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try
        {
            
            $this->logger->info("Enter UsersRestController.index()");
            
            //call service to get all users
            $service = new UserService($this->logger);
            $data = $service->getProfiles();
            
            $result = array();
            
            foreach($data as $user){
                
                $nu = new ProfileModel($user->getId(), $user->getUsername(), $user->getPassword(), $user->getFirstname(), $user->getLastname(), $user->getEmail(), $user->getCity(), $user->getState(), $user->getSuspended(), $user->getBirthday(), $user->getTagline(), $user->getPhoto());
                
                $id = $user->getId();
                $exp = $service->getExperience($id);
                $skills = $service->getSkills($id);
                $edu = $service->getEducation($id);
                
                $nu->setExperience($exp);
                $nu->setSkills($skills);
                $nu->setEducation($edu);
                
                array_push($result, $nu);
            }
            
            //create a DTO
            $dto = new DTO(200, "Good", $result);
            
            // Return the data
            $json = json_encode($dto);
            $this->logger->info("Exit UsersRestController.index() with success", array($json));
            return $json;
            
        }
        catch (Exception $ex)
        {
            $this->logger->error("Exception: ", array("message" => $ex->getMessage()));
            
            $dto = new DTO(500, $ex->getMessage, "");
            return json_encode($dto);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try
        {
            
            $this->logger->info("Enter UsersRestController.show()");
            
            // call service to get all users by id
            $service = new UserService($this->logger);
            $user = $service->getProfile($id);
            
            //create a DTO
            if($user == null){
                $dto = new DTO(404, "User not Found", "");
            }
            else {
                $nu = new ProfileModel($user->getId(), $user->getUsername(), $user->getPassword(), $user->getFirstname(), $user->getLastname(), $user->getEmail(), $user->getCity(), $user->getState(), $user->getSuspended(), $user->getBirthday(), $user->getTagline(), $user->getPhoto());
                
                $exp = $service->getExperience($id);
                $skills = $service->getSkills($id);
                $edu = $service->getEducation($id);
                
                $nu->setExperience($exp);
                $nu->setSkills($skills);
                $nu->setEducation($edu);
                $dto = new DTO(200, "Good", $nu);
            }
                    
            // Return the data
            $json = json_encode($dto);
            $this->logger->info("Exit UsersRestController.show() with success", array($json));
            return $json;
        }
        catch (Exception $e1)
        {
            $this->logger->error("Exception: ", array("message" => $e1->getMessage()));
            
            $dto = new DTO(500, $e1->getMessage(), "");
            return json_encode($dto);
        }
    }
    
}
