<?php

namespace App\Http\Controllers;

use App\Services\Utility\ILoggerService;
use Illuminate\Http\Request;

/*
 * Wrapper controller to return pages with incongruous controller/route naming, such as the welcome page being returned by JobController
 */
class WelcomeController extends Controller
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
     * Return the home page.
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Request $request){
        
        $this->logger->info("WelcomeControlller.index() - Accessing home page");
        // Return homepage with job data
        $j = new JobController($this->logger);
         $this->logger->info(" Exitign WelcomeControlller.index()");
        return $j->displayJobs($request);
        
    }
    
}
