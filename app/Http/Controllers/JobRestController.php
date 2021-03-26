<?php

namespace App\Http\Controllers;

use App\Services\Business\JobService;
use App\Services\Utility\ILoggerService;
use App\Models\DTO;
use Exception;

class JobRestController extends Controller
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
            
            $this->logger->info("Enter JobRestController.index()");
            
            //call service to get all jobs
            $service = new JobService($this->logger);
            $jobs = $service->getJobs();
            
            //create a DTO
            $dto = new DTO(200, "Good", $jobs);
            
            // Return the data
            $json = json_encode($dto);
            $this->logger->info("Exit JobRestController.index() with success", array($json));
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
            
            
            $this->logger->info("Enter JobRestController.show()");
            
            // call service to get all jobs by id
            $service = new JobService($this->logger);
            $job = $service->getJob($id);
            
            //create a DTO
            if($job->getId() == null)
                $dto = new DTO(404, "Job not Found", "");
            else
                $dto = new DTO(200, "Good", $job);
            
            // Return the data
            $json = json_encode($dto);
            $this->logger->info("Exit JobRestController.show() with success", array($json));
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
